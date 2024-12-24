<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use App\Http\Requests\ReceptionRequest;
use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Family;
use App\Models\Format;
use App\Models\Grooming;
use App\Models\GroomingStatusHistory;
use App\Models\Pet;
use App\Models\Prescription;
use App\Models\Producto;
use App\Models\Reason;
use App\Models\ReceptionStatusHistory;
use App\Models\RedSheet;
use App\Models\Room;
use App\Models\Surgery;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

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
        $pets = Pet::all(); // Aquí consultas todas las mascotas

        $this->authorize("create", Reception::class);
        return view('reception.create', compact('reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms', 'pets')); // Asegúrate de pasar $pets correctamente
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReceptionRequest $request)
    {
        $this->authorize("create", Reception::class);
        $reception = Reception::create($request->validated());

        ReceptionStatusHistory::create([
            'reception_id' => $reception->id,
            'attention_status_id' => 2,
        ]);

        if ($request->reception_type_id == 2) {
            return redirect()->route('hospital.list', ['id' => $reception->id])
                ->with('success', 'Recepción de hospitalización guardada exitosamente.');
        } elseif ($request->reception_type_id == 5) {
            return redirect()->route('new.cremation', ['id' => $reception->id])
                ->with('success', 'Recepción de cremación guardada exitosamente.');
        }

        if ($request->reception_type_id == 3) {
            GroomingStatusHistory::create([
                'reception_id' => $reception->id,
                'grooming_status_id' => 1,
            ]);
            return redirect()->route('receptions.grooming',  $reception->id);
        }

        return redirect()->route('receptions.index')
            ->with('success', 'Recepción guardada exitosamente.');
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
        $pets = Pet::all();
        $this->authorize("update", $reception);
        return view('reception.edit', compact('reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms', 'pets'));
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
        $receptions = Reception::with('receptionType', 'family', 'pet', 'reason', 'room', 'area')->get();
        return DataTables::of($receptions)->make(true);
    }

    // public function historial($id)
    // {
    //     $receptions = Reception::with('receptionType', 'reason', 'vet')->where('pet_id', $id)->get();
    //     $redSheet=RedSheet::with('reception')->where('reception_id->pet_id', $id)->get();
    //     $surgery=Surgery::with('reception')->where('reception_id->pet_id', $id)->get();
    //     $prescription=Prescription::where('pet_id', $id)->get();
    //     return DataTables::of($receptions, $redSheet, $surgery,$prescription)->make(true);
    // }

    public function historial($id)
    {
        $receptions = Reception::with('receptionType', 'reason', 'vet')->where('pet_id', $id)->get();

        $redSheets = RedSheet::whereHas('reception', function ($query) use ($id) {
            $query->where('pet_id', $id);
        })->with('reception')->get();

        $surgeries = Surgery::whereHas('reception', function ($query) use ($id) {
            $query->where('pet_id', $id);
        })->with('reception')->get();

        $prescriptions = Prescription::where('pet_id', $id)->get();

        $data = [];
        foreach ($receptions as $reception) {
            $data[] = [
                'entry_date' => $reception->entry_date,
                'vet_name' => $reception->vet->name ?? '',
                'reception_type' => $reception->receptionType->name ?? '',
                'reason' => $reception->reason->name ?? '',
                'reception_id' => $reception->id,
                'pet_id' => $reception->pet_id,
                'redSheets' => $redSheets->pluck('description')->toArray(),
                'surgeries' => $surgeries->pluck('surgery_type')->toArray(),
                'prescriptions' => $prescriptions->pluck('medicine')->toArray(),
            ];
        }

        return DataTables::of($receptions, $redSheets, $surgeries, $prescriptions)->make(true);
    }



    public function hospital_authorization($id)
    {
        $reception = Reception::find($id);
        $pet = Pet::with('family', 'genre')->find($id);
        return view('reception.pdf', compact("reception", "pet"));
    }

    public function hospital_authorizationpdf(Request $request, $id)
    {
        $reception = Reception::find($id);
        $pet = Pet::with('family', 'genre')->find($reception->pet_id);
        $signatureDataUrl = $request->input('signature');

        $pdf = PDF::loadView('reception.pdf', [
            'reception' => $reception,
            'pet' => $pet,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);

        $pdfPath = '/receptions/reception_' . $id . '.pdf';
        Storage::put('public' . $pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        $format = new Format();
        $format->format_type_id = 1;
        $format->reception_id = $id;
        $format->pet_id = $pet->id;
        $format->format_pdf = $pdfPath;
        $format->save();

        return response()->json(['url' => asset('storage' . $pdfPath), 'format_id' => $format->id]);
    }


    public function getFamilyByPet($pet_id)
    {
        $pet = Pet::find($pet_id);
        if ($pet && $pet->family) {
            return response()->json($pet->family);
        }
        return response()->json(null, 404);
    }

    public function transfer(Request $request, $id)
    {
        $reception = Reception::findOrFail($id);

        $reception->update($request->validate([
            'admission_type_id' => 'integer|exists:admission_types,id',
        ]));
        $this->authorize("update", $reception);

        return response()->json($reception);
    }

    public function cuenta($id)
    {
        $reception = Reception::with('pet')->where('id', $id)->first();
        $redSheets = RedSheet::where('reception_id', $id)->with('imaging', 'lab', 'service')->get();
        $surgeries = Surgery::where('reception_id', $id)->with('service')->get();

        $data = [
            'reception' => $reception,
            'redSheets' => $redSheets,
            'surgeries' => $surgeries,
        ];
        return DataTables::of($data)->make(true);
    }


    public function groomingservice(int $id)
    {
        $grooming = new Grooming();
        $products = Producto::where("ESTATUS",  "A")->get();
        $reception = Reception::find($id);
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::all();

        $this->authorize("create", Grooming::class);
        return view('grooming.create', compact('grooming', 'products', 'reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms', 'pets'));
    }
}
