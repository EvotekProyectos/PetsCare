<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use App\Http\Requests\ReceptionRequest;
use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Reason;
use App\Models\ReceptionStatusHistory;
use App\Models\Room;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

/**
 * Class ReceptionController
 * @package App\Http\Controllers
 */
class ReceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receptions = Reception::paginate();
        $this->authorize("viewAny", Reception::class);

        return view('reception.index', compact('receptions'))
            ->with('i', (request()->input('page', 1) - 1) * $receptions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reception = new Reception();
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();

        $this->authorize("create", Reception::class);
        return view('reception.create', compact('reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReceptionRequest $request)
    {
        $reception = Reception::create($request->validated());

        ReceptionStatusHistory::create([
            'reception_id' => $reception->id,
            'attention_status_id' => 2,
        ]);

        $this->authorize("create", Reception::class);
        return redirect()->route('receptions.index')
            ->with('success', 'Recepción guardada exitósamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reception = Reception::find($id);
        $this->authorize("view", Reception::class);
        return view('reception.show', compact('reception'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reception = Reception::find($id);
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $this->authorize("update", $reception);
        return view('reception.edit', compact('reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReceptionRequest $request, Reception $reception)
    {
        $reception->update($request->validated());
        $this->authorize("update", $reception);
        return redirect()->route('receptions.index')
            ->with('success', 'Recepción actualizada exitósamente.');
    }

    public function destroy($id)
    {
        $reception = Reception::find($id);
        $this->authorize("delete", $reception);
        $reception->delete();

        return response()->json($reception);
    }

    public function list()
    {
        $receptions = Reception::with('receptionType', 'family', 'pet', 'reason')->get();
        return DataTables::of($receptions)->make(true);
    }

    public function historial($id)
    {
        $receptions = Reception::with('receptionType', 'reason', 'vet')->where('pet_id', $id)->get();
        return DataTables::of($receptions)->make(true);
    }


    public function hospital_authorization($id) {
        $reception = Reception::find($id);
        $pet=Pet::with('family', 'genre')->find($id);
        return view('reception.pdf', compact("reception", "pet")); 
     }

     public function hospital_authorizationpdf(Request $request, $id)
     {
         $reception = Reception::find($id);
         $pet = Pet::with('family', 'genre')->find($id);
         
         $signature = $request->input('signature');

         $pdf = PDF::loadView('reception.pdf', compact('reception', 'pet', 'signature'));
         return $pdf->stream("hospitalizacion.pdf");
     }
 
    
     

    }

    