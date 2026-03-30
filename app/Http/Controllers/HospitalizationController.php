<?php

namespace App\Http\Controllers;

use App\Models\Hospitalization;
use App\Http\Requests\HospitalizationRequest;
use App\Models\FollowUp;
use App\Models\FollowupIntern;
use App\Models\FollowupSurgical;
use App\Models\Producto;
use App\Models\Format;
use App\Models\Pet;
use App\Models\Reception;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * Class HospitalizationController
 * @package App\Http\Controllers
 */
class HospitalizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospitalizations = Hospitalization::paginate();
        $this->authorize("viewAny", Hospitalization::class);
        return view('hospitalization.index', compact('hospitalizations'))
            ->with('i', (request()->input('page', 1) - 1) * $hospitalizations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hospitalization = new Hospitalization();
        $this->authorize("create", Hospitalization::class);
        return view('hospitalization.create', compact('hospitalization'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HospitalizationRequest $request)
    {
        Hospitalization::create($request->validated());
        $this->authorize("create", Hospitalization::class);
        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalization created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hospitalization = Hospitalization::find($id);
        $this->authorize("viewAny", $hospitalization);
        return view('hospitalization.show', compact('hospitalization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $hospitalization = Hospitalization::find($id);
        $this->authorize("update", $hospitalization);

        return view('hospitalization.edit', compact('hospitalization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HospitalizationRequest $request, Hospitalization $hospitalization)
    {
        $hospitalization->update($request->validated());
        $this->authorize("update", $hospitalization);

        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalization updated successfully');
    }

    public function destroy($id)
    {
        Hospitalization::find($id)->delete();

        return redirect()->route('hospitalizations.index')
            ->with('success', 'Hospitalization deleted successfully');
    }

    public function historic(int $id)
    {
        $reception = Reception::find($id); //buscamos la recepcion solicitada

        return view('hospitalization.historic', compact('reception')); //regresamos la vista del historial
    }

    public function followups(int $id)
    {
        //Buscamos a que recepcion corrersponden e instaciamos los nuevos registros
        $reception = Reception::find($id);
        $followupsCritic = new FollowUp();
        $followupIntern = new FollowupIntern();
        $followupSurgical = new FollowupSurgical();

        return view('follow-up.add', compact('reception', 'followupsCritic', 'followupIntern', 'followupSurgical')); //regresamos la infa a la vista
    }


    public function altaVoluntaria($id)
    {
        $reception = Reception::find($id); //encuentra la recepcion
        return view('hospital-discharge.alta_voluntaria', compact("reception")); //regresa vista del pdf con la recedpcion para llenado y firma
    }

    public function altaVoluntariapdf(Request $request, $id)
    {
        $reception = Reception::find($id); //buscamos la recepcion correspondiente
        $pet = Pet::with('family', 'genre')->find($reception->pet_id); //buscamos la mascota correspondiente

        //recuperamos los datos que lleno el propietario
        $signatureDataUrl = $request->input('signature');
        $nameFamily = $request->input('name_family');
        $reason = $request->input('reason');

        // Generar el PDF con los datos de la recepción,  mascota y llenado del propietario
        $pdf = PDF::loadView('hospital-discharge.alta_voluntaria', [
            'reception' => $reception,
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
            'nameFamily' => $nameFamily,
            'reason' => $reason,
            'isPdf' => true
        ]);

        // Guardar el PDF en el almacenamiento público
        $pdfPath = 'public/hospitalizations/voluntary_discharge_' . $id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath); // Obtener la URL pública del PDF

        //Guardamos el formato en la tabla correspondiente
        $format = new Format();
        $format->format_type_id = 2;
        $format->reception_id = $id;
        $format->pet_id = $pet->id;
        $format->format_pdf = $pdfPath;
        $format->save();

        // Retornar la respuesta JSON con la URL del PDF y el ID del formato generado
        return response()->json(['url' => asset($pdfUrl), 'format_id' => $format->id]);
    }

    public function dischargeDeath(Request $request)
    {
        $reception = Reception::findOrFail($request->receptionId); //buscaos la recepcion
        $reception->exit_date = now(); //marcamos la fecha y hora de salida
        $reception->save();

        //creamos registro en hospitlizacion para registrar el tipo de salida
        $hospitalization = new Hospitalization();
        $hospitalization->reception_id = $request->receptionId;
        $hospitalization->exit_date = now();
        $hospitalization->hospital_discharges_id = 3;
        $hospitalization->save();

        // Actualizar vouchers pendientes a cancelados
        Voucher::where('reception_id', $request->receptionId)
            ->where('status', 'Pendiente')
            ->update([
                'status' => 'Cancelado',
                'cancellation_reason' => 'El paciente falleció'
            ]);


        return response()->json([
            'message' => 'Paciente dado de alta por fallecimiento.',
        ], 200); //regresamos mensaje 
    }

    public function discharge(Request $request)
    {
        $reception = Reception::findOrFail($request->reception_id); //buscaos la recepcion
        $reception->exit_date = now(); //marcamos la fecha y hora de salida 
        $reception->save();

        // Actualizar vouchers pendientes a cancelados
        Voucher::where('reception_id', $request->reception_id)
            ->where('status', 'Pendiente')
            ->update([
                'status' => 'Cancelado',
                'cancellation_reason' => 'El paciente fue dado de alta'
            ]);

        $data = $request->all();
        $data['exit_date'] = now();
        $new = Hospitalization::create($data); //registreamos el tipo de sdalida 

        return response()->json($new); //regresamos el nuevo registro
    }
}
