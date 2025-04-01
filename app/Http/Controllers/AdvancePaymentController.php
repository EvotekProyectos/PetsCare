<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Http\Requests\AdvancePaymentRequest;
use App\Models\Reception;
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
        $reference = $this->reference($reception_id)->getData()->reference;
        $data = $request->validated();
        $data['reference'] = $reference;
        $data['status'] = '0';

        $advancePayment = AdvancePayment::create($data);
        $this->authorize("create", $advancePayment);

        return redirect()->route('advance-payments.show', $advancePayment->id);
            // ->with('success', 'AdvancePayment created successfully.');
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
