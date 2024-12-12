<?php

namespace App\Http\Controllers;

use App\Models\Surgery;
use App\Http\Requests\SurgeryRequest;
use App\Models\Format;
use App\Models\Hospitalization;
use App\Models\Pet;
use App\Models\ProductClassification;
use App\Models\Producto;
use App\Models\ProductType;
use App\Models\Reception;
use App\Models\RedSheet;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * Class SurgeryController
 * @package App\Http\Controllers
 */
class SurgeryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surgeries = Surgery::paginate();
        $this->authorize("viewAny", Surgery::class);
        return view('surgery.index', compact('surgeries'))
            ->with('i', (request()->input('page', 1) - 1) * $surgeries->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $surgery = new Surgery();
        $products= ProductType::all();
        $reception = Reception::find($id);
        $this->authorize("create", Surgery::class);

        return view('surgery.create', compact('surgery',  'products', 'reception'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurgeryRequest $request)
    {
        $this->authorize("create", Surgery::class);
        $surgery = new Surgery($request->validated());
        $surgery->vet_id = auth()->id();
        $surgery->save();

        return response()->json($surgery);
        // return redirect()->route('surgeries.index')
        //     ->with('success', 'Surgery created successfully.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $surgery = Surgery::find($id);

        return view('surgery.show', compact('surgery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $surgery = Surgery::find($id);
    $reception = $surgery->reception;
    $vets= $surgery->user;
    $products = ProductType::all(); 

    $this->authorize("update", $surgery);

    return view('surgery.edit', compact('surgery', 'reception', 'products', 'vets'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(SurgeryRequest $request, Surgery $surgery)
    {
        $surgery->update($request->validated());

        return redirect()->route('surgeries.index')
            ->with('success', 'Surgery updated successfully');
    }

    public function destroy($id)
    {
        Surgery::find($id)->delete();

        return redirect()->route('surgeries.index')
            ->with('success', 'Surgery deleted successfully');
    }

    public function entry($id)
    {
        $surgery = Surgery::with('vet', 'surgery')->where('reception_id', $id)->get();

        return DataTables::of($surgery) ->make(true);
    }

    public function checkRequirements($id)
    {
        $hasLabAndImaging = RedSheet::where('reception_id', $id)
            ->whereNotNull('lab_type_id')
            ->whereNotNull('imaging_type_id')
            ->exists();
    
        if ($hasLabAndImaging) {
            return response()->json(['status' => 'ok']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Se requiere al menos un registro de laboratorio y uno de imagenología.']);
        }
    }

    public function surgery_authorization($id)
    {
        $reception = Reception::find($id);
        $products = Producto::where("ESTATUS",  "A")->get();
        $pet = Pet::with('family', 'genre')->find($id);
        return view('surgery.aut_quirurgica', compact("reception", "pet", "products"));
    }

    public function surgery_authorizationpdf(Request $request, $id)
    {
        $reception = Reception::with('pet')->find($id);
        $pet = $reception->pet;
        

        $signatureDataUrl = $request->input('signature');
         $procedure = $request->input('procedure');
          $total= $request->input('total');
          $include = $request->input('include');
    
        $pdf = PDF::loadView('surgery.aut_quirurgica', [
            'reception' => $reception,
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
          'procedure' => $procedure,
          'total' => $total,
          'include' => $include,
            'isPdf' => true
        ]);

        $pdfPath = 'public/hospitalizations/auth_surgery_' . $id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        $format = new Format();
        $format->format_type_id = 3; 
        $format->reception_id = $id;
        $format->pet_id= $pet->id;
        $format->format_pdf = $pdfPath; 
        $format->save();

        return response()->json([ 'url' => asset($pdfUrl), 'format_id' => $format->id]);
        //return response()->json(['url' => $pdfUrl, 'format_id' => $format->id]);
        //return response()->json(['url' => asset('storage'.$pdfPath), 'format_id' => $format->id]);
    }

}
