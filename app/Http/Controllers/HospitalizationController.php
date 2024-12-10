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

    public function historic(int $id){
        $reception = Reception::find($id);

        return view('hospitalization.historic', compact('reception'));
    }

    public function followups(int $id){
        $reception = Reception::find($id);
        $followupsCritic = new FollowUp();
        $followupIntern = new FollowupIntern();
        $followupSurgical = new FollowupSurgical();

        return view('follow-up.add', compact('reception', 'followupsCritic', 'followupIntern', 'followupSurgical'));
    }

    public function test(){
        dd(Producto::all()); 
    }
    public function altaVoluntaria($id){
        $reception = Reception::find($id);
        $pet = Pet::with('family', 'genre')->find($id);
        return view('hospital-discharge.alta_voluntaria', compact("reception", "pet"));
    }

    public function altaVoluntariapdf(Request $request, $id)
    {
        $reception = Reception::find($id);
        $pet = Pet::with('family', 'genre')->find($reception->pet_id);

        $reception->exit_date = now();
        $reception->save();

        
        $signatureDataUrl = $request->input('signature');
         $nameFamily = $request->input('name_family');
         $reason = $request->input('reason');
    
        $pdf = PDF::loadView('hospital-discharge.alta_voluntaria', [
            'reception' => $reception,
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
             'nameFamily' => $nameFamily,
             'reason' => $reason,
            'isPdf' => true
        ]);

        $pdfPath = 'public/hospitalizations/voluntary_discharge_' . $id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        $format = new Format();
        $format->format_type_id = 2; 
        $format->reception_id = $id;
        $format->pet_id= $pet->id;
        $format->format_pdf = $pdfPath; 
        $format->save();

        $hospitalization = new Hospitalization();
        $hospitalization->reception_id = $id;  
        $hospitalization->exit_date = now();
        $hospitalization->hospital_discharges_id = 2; 
        $hospitalization->save();

        return response()->json(['url' => $pdfUrl, 'format_id' => $format->id]);
    }


}
