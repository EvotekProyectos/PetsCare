<?php

namespace App\Http\Controllers;

use App\Models\Format;
use App\Http\Requests\FormatRequest;
use App\Models\FormatType;
use App\Models\Hospitalization;
use App\Models\Pet;
use App\Models\Reception;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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

    public function store(FormatRequest $request)
    {
        Format::create($request->validated());
        $this->authorize("create", Format::class);

        return redirect()->route('formats.index')
            ->with('success', 'Format created successfully.');
    }


    public function show($id)
    {
        $format = Format::find($id);
         $this->authorize("viewAny", $format);
        return view('format.show', compact('format'));
    }

   
    public function edit($id)
    {
        $format = Format::find($id);
        $this->authorize("update", $format);
        return view('format.edit', compact('format'));
    }

   
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

    public function list()
    {
        $formats = Format::with(['reception.pet', 'formatType','reception.receptionType','pet'])->get();
        return DataTables::of($formats)->make(true);
    }


     public function listOne($id)
     {
         $pet = Pet::findOrFail($id); 
         $receptions = $pet->receptions;
         $formats = Format::with(['reception.pet', 'formatType','reception.receptionType','pet'])
         ->whereIn('reception_id', $receptions->pluck('id')->toArray())->orWhere('pet_id', $id)->get();
      
         return DataTables::of($formats)->make(true);  
      }


    //   public function format_list($id)
    //   {
    //       $this->authorize("viewAny", Format::class);
    //       $pet = Pet::findOrFail($id); 
    //       return view('format.view', compact('pet'));
    //   }
    public function format_list($id)
    {
        $this->authorize("viewAny", Format::class);
        $pet = Pet::findOrFail($id);
        $formats = Format::where('pet_id', $id)->paginate();
    
        return view('format.view', compact('formats', 'pet'))
            ->with('i', (request()->input('page', 1) - 1) * $formats->perPage());
    }
    

    public function hospital_authorization($id)
    {
        $pet = Pet::with('family', 'genre')->find($id);
        return view('format.aut_hospital', compact( "pet"));
    }



    // public function generateHospitalAuthorizationPdf(Request $request, $id)
    // {
    //     $pet = Pet::with('family', 'genre')->find($id);
    //     $signatureDataUrl = $request->input('signature');
    
    //     $uniqueId = uniqid(); 
    //     $pdfPath = 'public/formats/auth_hospital' . $id . '_' . $uniqueId . '.pdf';
    
    //     $pdf = PDF::loadView('format.aut_hospital', [
    //         'pet' => $pet,
    //         'signatureDataUrl' => $signatureDataUrl,
    //         'isPdf' => true
    //     ]);
    
    //     Storage::put($pdfPath, $pdf->output());
    //     $pdfUrl = Storage::url($pdfPath);
    
    //     $format = new Format();
    //     $format->format_type_id = 1;
    //     $format->pet_id = $id;
    //     $format->format_pdf = $pdfPath;
    //     $format->save();

    //     $hospitalization = new Hospitalization();
    //     $hospitalization->pet_id = $id;
    //     $hospitalization->save();

    //     //return response()->json(['url' => asset('storage'.$pdfPath), 'format_id' => $format->id]);
    
    //     return response()->json([ 'url' => asset($pdfUrl), 'format_id' => $format->id]);
        
    //    // return response()->json(['url' => $pdfUrl, 'format_id' => $format->id]);
    // }

    public function generateHospitalAuthorizationPdf(Request $request, $id)
    {
        $pet = Pet::with('family', 'genre')->find($id);
        $signatureDataUrl = $request->input('signature');
    
        $uniqueId = uniqid(); 
        $pdfPath = 'public/formats/auth_hospital' . $id . '_' . $uniqueId . '.pdf';
    
        $pdf = PDF::loadView('format.aut_hospital', [
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);
    
        Storage::put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);
        //$pdfUrl = asset('storage/' . str_replace('public/', '', $pdfPath));

    
        $format = new Format();
        $format->format_type_id = 1;
        $format->pet_id = $id;
        $format->format_pdf = $pdfPath;
        $format->save();

        $hospitalization = new Hospitalization();
        $hospitalization->pet_id = $id;
        $hospitalization->save();

        //return response()->json(['url' => asset('storage'.$pdfPath), 'format_id' => $format->id]);
    
        return response()->json([ 'url' => asset($pdfUrl), 'format_id' => $format->id]);
        
       // return response()->json(['url' => $pdfUrl, 'format_id' => $format->id]);
    }


    public function altaVoluntaria($id){
        $pet = Pet::with('family', 'genre')->find($id);
        return view('format.alta', compact("pet"));
    }

    public function altaVoluntariapdf(Request $request, $id)
    {
        $pet = Pet::with('family', 'genre')->find($id);

        $signatureDataUrl = $request->input('signature');
         $nameFamily = $request->input('name_family');
         $reason = $request->input('reason');

         $uniqueId = uniqid(); 
         $pdfPath = 'public/formats/voluntary_discharge_' . $id .' _'. $uniqueId.'.pdf';
    
        $pdf = PDF::loadView('format.alta', [
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
             'nameFamily' => $nameFamily,
             'reason' => $reason,
            'isPdf' => true
        ]);

        Storage::put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);

        $format = new Format();
        $format->format_type_id = 2; 
        $format->pet_id = $id;
        $format->format_pdf = $pdfPath; 
        $format->save();

        $hospitalization = new Hospitalization();
        $hospitalization->pet_id = $id;
        $hospitalization->exit_date = now();
        $hospitalization->hospital_discharges_id = 2; 
        $hospitalization->save();
        
        return response()->json([ 'url' => asset($pdfUrl), 'format_id' => $format->id]);
        //return response()->json(['url' => $pdfUrl, 'format_id' => $format->id]);
    }

    public function surgery_authorization($id)
    {
        $pet = Pet::with('family', 'genre')->find($id);
        return view('format.aut_surgery', compact( "pet"));
    }
    
    public function surgery_authorizationpdf(Request $request, $id)
    {
        $pet = Pet::with('family', 'genre')->find($id);
        $signatureDataUrl = $request->input('signature');
         $procedure = $request->input('procedure');
          $total= $request->input('total');
          $include = $request->input('include');
    
         $uniqueId = uniqid(); 
         $pdfPath = 'public/formats/auth_surgery_' . $id .'_'.$uniqueId. '.pdf';

        $pdf = PDF::loadView('format.aut_surgery', [
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
          'procedure' => $procedure,
          'total' => $total,
          'include' => $include,
            'isPdf' => true
        ]);

        Storage::put($pdfPath, $pdf->output());
        $pdfUrl = Storage::url($pdfPath);

        $format = new Format();
        $format->format_type_id = 3; 
        $format->pet_id= $id;
        $format->format_pdf = $pdfPath; 
        $format->save();

        return response()->json([ 'url' => asset($pdfUrl), 'format_id' => $format->id]);
        //return response()->json(['url' => $pdfUrl, 'format_id' => $format->id]);
    }

    public function reporte()
    {
        return view('format.reporte_ultrasono');
    }
}