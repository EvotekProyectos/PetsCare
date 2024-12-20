<?php

namespace App\Http\Controllers;

use App\Models\Cremation;
use App\Http\Requests\CremationRequest;
use App\Models\CmType;
use App\Models\Producto;
use App\Models\Reception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CremationController
 * @package App\Http\Controllers
 */
class CremationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cremations = Cremation::paginate();
        $this->authorize("viewAny", Cremation::class);
        return view('cremation.index', compact('cremations'))
            ->with('i', (request()->input('page', 1) - 1) * $cremations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cremation = new Cremation();
        $cremation->status= 'En espera de realizar';
        $cms= CmType::all();
        $this->authorize("create", Cremation::class);
        return view('cremation.create', compact('cremation', 'cms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CremationRequest $request)
    {
        $new= Cremation::create($request->validated());
        
        $this->authorize("create", Cremation::class);

        //return redirect()->route('cremations.index')
           // ->with('success', 'Cremation created successfully.');
        return response()->json($new);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cremation = Cremation::find($id);
        $this->authorize("view", Cremation::class);
        return view('cremation.show', compact('cremation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cremation = Cremation::find($id);
        $reception= $cremation->reception;
        $cms= CmType::all();
        $products = Producto::where("ESTATUS",  "A")->get();

        $this->authorize("update", $cremation);
        return view('cremation.edit', compact('cremation', 'reception', 'cms', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CremationRequest $request, Cremation $cremation)
    {
        $cremation->update($request->validated());
        $this->authorize("update", $cremation);
        return redirect()->route('cremations.index')
            ->with('success', 'Cremación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $cremation= Cremation::find($id);
        $this->authorize("delete", $cremation);
        $cremation->delete();

        return response()->json($cremation);
        //return redirect()->route('cremations.index')
          //  ->with('success', 'Cremation deleted successfully');
    }

    
    public function new(int $id)
    {
        $cremation = new Cremation();
        $cremation->status= "En espera de realizar";
      
        $reception = Reception::with('pet', 'reason', 'vet', 'receptionist')->findorfail($id);
        $cms= CmType::all();
        $products = Producto::where("ESTATUS",  "A")->get();

        return view('cremation.create', compact('cremation', 'reception', 'cms', 'products'));
    }

     public function comprobante(int $id){
         $cremation = Cremation::with("reception", "pet")->find($id);
         $reception=$cremation->reception_id;

         //$next=Appointment::where("reception_id", $reception)->get()->First();       
         $pdf = Pdf::loadView("cremation.comprobante", compact("cremation", "reception"));
         return $pdf->stream('comprobante.pdf');
     }


    public function list()
    {
        $cremations = Cremation::with('reception','reception.vet' ,'reception.family','pet', 'cm', 'tag')->get();
        //return DataTables::of($cremations)->make(true);
        return response()->json($cremations);
    }

    public function updateStatus($id, Request $request)
{
    $cremation = Cremation::findOrFail($id);
    $cremation->status = $request->status;
    $cremation->save();

    return response()->json(['success' => true, 'status' => $cremation->status]);
}

}
