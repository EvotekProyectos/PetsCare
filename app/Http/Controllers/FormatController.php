<?php

namespace App\Http\Controllers;

use App\Models\Format;
use App\Http\Requests\FormatRequest;
use App\Models\FormatType;
use App\Models\Pet;
use App\Models\Reception;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Illuminate\Http\Request;

/**
 * Class FormatController
 * @package App\Http\Controllers
 */
class FormatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formats = Format::paginate();
        $this->authorize("viewAny", Format::class);
        return view('format.index', compact('formats'))
            ->with('i', (request()->input('page', 1) - 1) * $formats->perPage());
    }
    
        public function list($id)
        {
        $pet = Pet::findOrFail($id);
            $receptions = $pet->receptions;
            $formats = Format::whereIn('reception_id', $receptions->pluck('id'))->orWhere('pet_id', $id)->get();
            $this->authorize("viewAny", Format::class);
          return view('format.view', compact('formats', 'pet', 'receptions'));
         
     }


    

    public function hospital_authorization($id)
    {
        $pet = Pet::with('family', 'genre')->find($id);
        return view('format.aut_hospital', compact( "pet"));
    }

    public function generateHospitalAuthorizationPdf(Request $request, $id)
    {
        $reception = Reception::find($id);
        $pet = Pet::with('family', 'genre')->find($id);
        $signatureDataUrl = $request->input('signature');
    
        $uniqueId = uniqid(); 
        $pdfPath = 'public/formats/pet_' . $id . '_' . $uniqueId . '.pdf';
    
        $pdf = PDF::loadView('reception.pdf', [
            'reception' => $reception,
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);
    
        Storage::put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);
    
        $format = new Format();
        $format->format_type_id = 1;
        $format->pet_id = $id;
        $format->format_pdf = $pdfPath;
        $format->save();
    
        return response()->json(['url' => $pdfUrl, 'format_id' => $format->id]);
    }
    
   

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $format = new Format();
        $format_types= FormatType::all();
        $this->authorize("create", Format::class);
        return view('format.create', compact('format', 'format_types'));
    }


    public function add($id)
    {   
        $pet = Pet::find($id);
        $format = new Format();
        $format_types= FormatType::all();
        $this->authorize("create", Format::class);
        return view('format.create', compact('format','pet', 'format_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormatRequest $request)
    {
        Format::create($request->validated());
        $this->authorize("create", Format::class);

        return redirect()->route('formats.index')
            ->with('success', 'Format created successfully.');
    }

    




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $format = Format::find($id);
         $this->authorize("view", $format);
        return view('format.show', compact('format'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $format = Format::find($id);
        $this->authorize("update", $format);
        return view('format.edit', compact('format'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormatRequest $request, Format $format)
    {
        $format->update($request->validated());
        $this->authorize("update", $format);
        return redirect()->route('formats.index')
            ->with('success', 'Format updated successfully');
    }

    public function destroy($id)
    {
        $format=Format::find($id);
        $this->authorize("update", $format);
        $format->delete();

        return response()->json($format);
    }
}
