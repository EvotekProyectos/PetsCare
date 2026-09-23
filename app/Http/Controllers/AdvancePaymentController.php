<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Http\Requests\AdvancePaymentRequest;
use App\Models\Reception;
use App\Services\AccountStatementService;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class AdvancePaymentController
 * @package App\Http\Controllers
 */
class AdvancePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize("viewAny", AdvancePayment::class);
        $advancePayments = AdvancePayment::paginate();

        return view('advance-payment.index', compact('advancePayments'))
            ->with('i', (request()->input('page', 1) - 1) * $advancePayments->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $advancePayment = new AdvancePayment();
        $fechaActual = now()->format('Y-m-d\TH:i');
        $fechaGuardada = $advancePayment?->date ? $advancePayment->date->format('Y-m-d\TH:i') : $fechaActual;

        $this->authorize("create", $advancePayment);
        return view('advance-payment.create', compact('advancePayment', 'fechaGuardada'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdvancePaymentRequest $request)
    {
        $reception_id = $request->reception_id;
        $reception = $reception_id ? Reception::find($reception_id) : null;

        // El anticipo debe quedar ligado a la cuenta de la recepción desde la
        // que se está registrando (ver AccountStatementService/Episode/Account).
        // No se le pide account_id al frontend: se resuelve aquí, en el único
        // lugar que ya conoce con certeza la recepción de origen.
        $account = $reception?->episode?->account;

        if (!$account) {
            $message = 'No se pudo registrar el anticipo: debe generarse desde una recepción con una cuenta válida.';

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return redirect()->back()->withInput()->with('error', $message);
        }

        $reference = $this->reference($reception_id)->getData()->reference;
        $data = $request->validated();
        $data['reference'] = $reference;
        $data['status'] = '0';
        $data['account_id'] = $account->id;
        // La fecha ya no la captura el usuario (ver modal de creación en
        // reception/partials/advance-payment-modal.blade.php): siempre es el
        // momento real del registro, tomado del servidor.
        $data['date'] = now();

        $advancePayment = AdvancePayment::create($data);
        $this->authorize("create", $advancePayment);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'id' => $advancePayment->id,
                'amount' => $advancePayment->amount,
                'concept' => $advancePayment->concept,
            ]);
        }

        return redirect()->route('advance-payments.show', $advancePayment->id);
            // ->with('success', 'AdvancePayment created successfully.');
    }

    /**
     * Pago de consulta previo a admitir una hospitalización trasladada (ver
     * ReceptionTransferController::seedInitialStatusHistory() y
     * ReceptionController::hospital_authorization()). Reutiliza el mismo
     * modelo/tabla que un anticipo normal (AdvancePayment): la única
     * diferencia real es que el concepto queda fijo y el monto tiene un
     * mínimo obligatorio (el saldo pendiente de la consulta), ambos
     * validados aquí como fuente de verdad — el navegador nunca decide ni
     * el concepto ni si el monto alcanza.
     */
    public function payConsulta(AdvancePaymentRequest $request, int $receptionId)
    {
        $reception = Reception::find($receptionId);
        $account = $reception?->episode?->account;

        if (!$account) {
            $message = 'No se pudo registrar el pago: la recepción no tiene una cuenta válida.';

            return response()->json(['success' => false, 'message' => $message], 422);
        }

        $balance = app(AccountStatementService::class)->consultaBalance($reception);

        if ((float) $request->amount < $balance) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'amount' => ['El monto debe cubrir el saldo pendiente de la consulta ($' . number_format($balance, 2) . ').'],
                ],
            ], 422);
        }

        $reference = $this->reference($receptionId)->getData()->reference;

        // status=1 ("confirmado"): a diferencia de un anticipo genérico
        // (store(), status=0), este pago se cobra en mostrador como
        // requisito para admitir la hospitalización — es dinero real ya
        // recibido, no una provisión. AccountStatementService::
        // hasConfirmedConsultaPayment() usa exactamente esta señal (más el
        // monto) para decidir si el botón "Documentos" ya puede mostrarse.
        $advancePayment = AdvancePayment::create([
            'reception_id' => $receptionId,
            'account_id' => $account->id,
            'reference' => $reference,
            'concept' => 'Pago de consulta',
            'amount' => $request->amount,
            'status' => 1,
            'date' => now(),
        ]);
        $this->authorize("create", $advancePayment);

        return response()->json([
            'success' => true,
            'id' => $advancePayment->id,
            'amount' => $advancePayment->amount,
            'concept' => $advancePayment->concept,
        ]);
    }

    /**
     * Cobro combinado de Recepción para una hospitalización (trasladada o
     * directa): saldo de Consulta + anticipo de servicios hospitalarios
     * (ver AccountStatementService::paymentMinimumRequired()). Ya NO es
     * requisito para que el médico trabaje en Red Sheet -eso lo decide
     * RedSheetController::entry(), sin depender de ningún pago-; esto es
     * puramente el cobro en mostrador.
     *
     * El monto mínimo se recalcula aquí mismo con los servicios/anticipos
     * ACTUALES (nunca se confía en un mínimo mandado por el frontend, ver
     * AdvancePaymentRequest). Si hay saldo de consulta pendiente, se cubre
     * primero con su propio registro (mismo concepto "Pago de consulta" que
     * ya usa payConsulta(), para no crear una segunda forma de identificar
     * ese concepto); el resto, si corresponde, se registra como "Anticipo
     * de hospitalización". Hoy el anticipo requerido es 0.0 (regla de
     * negocio pendiente, ver hospitalizacionAnticipoRequerido()), así que en
     * la práctica esto se comporta igual que pagar solo la consulta hasta
     * que esa regla se defina — sin que este método necesite otro cambio.
     */
    public function payHospitalizacion(AdvancePaymentRequest $request, int $receptionId)
    {
        $reception = Reception::find($receptionId);
        $account = $reception?->episode?->account;

        if (!$account) {
            $message = 'No se pudo registrar el pago: la recepción no tiene una cuenta válida.';

            return response()->json(['success' => false, 'message' => $message], 422);
        }

        $statementService = app(AccountStatementService::class);
        $consultaBalance = $statementService->consultaBalance($reception);
        $anticipoRequerido = $statementService->hospitalizacionAnticipoRequerido($reception);
        $minimoRequerido = $consultaBalance + $anticipoRequerido;

        $amount = (float) $request->amount;

        if ($amount < $minimoRequerido) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'amount' => ['El monto debe cubrir el mínimo requerido ($' . number_format($minimoRequerido, 2) . ').'],
                ],
            ], 422);
        }

        $reference = $this->reference($receptionId)->getData()->reference;
        $created = [];

        $restante = $amount;

        if ($consultaBalance > 0) {
            $montoConsulta = min($restante, $consultaBalance);

            $payment = AdvancePayment::create([
                'reception_id' => $receptionId,
                'account_id' => $account->id,
                'reference' => $reference,
                'concept' => 'Pago de consulta',
                'amount' => $montoConsulta,
                'status' => 1,
                'date' => now(),
            ]);
            $this->authorize("create", $payment);
            $created[] = $payment;

            $restante -= $montoConsulta;
        }

        // Con la regla de anticipo aún sin definir esto no se ejecuta hoy
        // ($anticipoRequerido siempre es 0.0), pero queda listo: cuando se
        // defina, cualquier remanente por encima del saldo de consulta se
        // registra aparte, con su propio concepto.
        if ($anticipoRequerido > 0 && $restante > 0) {
            $payment = AdvancePayment::create([
                'reception_id' => $receptionId,
                'account_id' => $account->id,
                'reference' => $reference,
                'concept' => 'Anticipo de hospitalización',
                'amount' => $restante,
                'status' => 1,
                'date' => now(),
            ]);
            $this->authorize("create", $payment);
            $created[] = $payment;
        }

        return response()->json([
            'success' => true,
            'payments' => collect($created)->map(fn ($p) => [
                'id' => $p->id,
                'concept' => $p->concept,
                'amount' => $p->amount,
            ]),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $advancePayment = AdvancePayment::find($id);
        $this->authorize("viewAny", $advancePayment);

        return view('advance-payment.show', compact('advancePayment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $advancePayment = AdvancePayment::find($id);
        $fechaActual = now()->format('Y-m-d\TH:i');
        $fechaGuardada = $advancePayment?->date ? $advancePayment->date : $fechaActual;

        $this->authorize("update", $advancePayment);

        return view('advance-payment.edit', compact('advancePayment', 'fechaGuardada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdvancePaymentRequest $request, AdvancePayment $advancePayment)
    {
        $advancePayment->update($request->validated());

        $this->authorize("update", $advancePayment);
        return redirect()->route('advance-payments.index')
            ->with('success', 'AdvancePayment updated successfully');
    }

    public function destroy($id)
    {
        $advancePayment = AdvancePayment::find($id);
        $this->authorize("delete", $advancePayment);
        $advancePayment->delete();

        return response()->json($advancePayment);
    }

    public function list()
    {
        $this->authorize("viewAny", AdvancePayment::class);
        $advances = AdvancePayment::with(
            'reception',
            'reception.receptionType',
            'reception.family',
            'reception.pet',
            'user'
        )->get();

        return DataTables::of($advances)->make(true);
    }

    public function add(int $id)
    {
        $advancePayment = new AdvancePayment();
        $reception = Reception::findOrfail($id);
        $fechaActual = now()->format('Y-m-d\TH:i');
        $fechaGuardada = $advancePayment?->date ? $advancePayment->date->format('Y-m-d\TH:i') : $fechaActual;

        $this->authorize("create", $advancePayment);
        return view('advance-payment.create', compact('advancePayment', 'fechaGuardada', 'reception'));
    }

    public function reference(int $id)
    {
        $reception = Reception::find($id); //Obtener la info de la recepcion

        $datePart = date('ymd'); //obtener fecha actual
        $receptionPart = str_pad($reception->id, 2, '0', STR_PAD_LEFT); //id de la recepcion asegurando dos digitos
        $familyPart = str_pad($reception->family_id ?? 0, 2, '0', STR_PAD_LEFT); //id de la familia asegurando dos digitos
        $petPart = str_pad($reception->pet_id ?? 0, 2, '0', STR_PAD_LEFT); //id de la mascota con al menos dos digitos
        $randomLetter = chr(rand(65, 90)); // letra aleatoria
        $randomNumber = rand(0, 9); //numero aleatroio

        // Concatenar la referencia
        $reference = "{$datePart}{$receptionPart}{$familyPart}{$petPart}{$randomLetter}{$randomNumber}";

        return response()->json(['reference' => $reference]);
    }

    public function pdf(int $id)
    {
        $this->authorize("viewAny", AdvancePayment::class);
        $advancePayment = AdvancePayment::with('reception' )->find($id);

        $pdf = PDF::loadView('advance-payment.pdf', compact('advancePayment'))
            ->setPaper([0, 0, 226.77, 500]); // 80mm de ancho, largo dinámico
        return $pdf->stream('recibo.pdf');
    }
}
