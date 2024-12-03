<?php

namespace App\Http\Controllers;

use App\Models\RedSheet;
use App\Http\Requests\RedSheetRequest;
use App\Models\AdmissionType;
use App\Models\FollowUp;
use App\Models\HospitalDischarge;
use App\Models\Hospitalization;
use App\Models\Log;
use App\Models\Prescription;
use App\Models\ProductType;
use App\Models\Reception;
use App\Models\Surgery;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class RedSheetController
 * @package App\Http\Controllers
 */
class RedSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $redSheets = RedSheet::paginate();
        $this->authorize("viewAny", RedSheet::class);

        return view('red-sheet.index', compact('redSheets'))
            ->with('i', (request()->input('page', 1) - 1) * $redSheets->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $redSheet = new RedSheet();
        $this->authorize("create", RedSheet::class);
        return view('red-sheet.create', compact('redSheet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RedSheetRequest $request)
    {
        $new = RedSheet::create($request->validated());
        $this->authorize("create", RedSheet::class);

        return response()->json($new);

        // return redirect()->route('red-sheets.index')
        //     ->with('success', 'RedSheet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("view", $redSheet);

        return view('red-sheet.show', compact('redSheet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("update", $redSheet);

        return view('red-sheet.edit', compact('redSheet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RedSheetRequest $request, RedSheet $redSheet)
    {
        $redSheet->update($request->validated());
        $this->authorize("update", $redSheet);

        return redirect()->route('red-sheets.index')
            ->with('success', 'RedSheet updated successfully');
    }

    public function destroy($id)
    {
        $redSheet = RedSheet::find($id);
        $this->authorize("delete", $redSheet);
        $redSheet->delete();

        return response()->json($redSheet);
    }

    public function entry($id)
    {
        $redSheet = new RedSheet();
        $reception = Reception::with('pet', 'admissionType', 'area')->findorfail($id);
        $products = ProductType::all();
        $followUp = new FollowUp();
        $surgery = new Surgery();
        $admissions = AdmissionType::all();
        $this->authorize("create", RedSheet::class);
        return view('red-sheet.create', compact('redSheet', 'reception', 'products', 'followUp', 'surgery', 'admissions'));
    }

    public function recap(int $id)
    {
        $redsheets = RedSheet::with('vet', 'imaging', 'lab', 'service')->where('reception_id', $id)->get();
        $surgeries = Surgery::with('surgery','vet')->where('reception_id', $id)->get();

        // return DataTables::of($surgeries) ->make(true);
        $combinedData = $surgeries->map(function ($surgery) use ($redsheets) {
            $surgeryDate = \Carbon\Carbon::parse($surgery->date)->format('Y-m-d');

            $date_count = 0;
        
            $matchingRedSheet = $redsheets->first(function ($redsheet) use ($surgeryDate) {
                $redsheetDate = \Carbon\Carbon::parse($redsheet->created_at)->format('Y-m-d');
                return $redsheetDate == $surgeryDate;  
            });
        
            if ($matchingRedSheet) {
                $date_count = $matchingRedSheet->day_count; 
            }
            $surgery->setAttribute('day_count', $date_count);
        
            return $surgery;
        });

        $allData = [
            'surgeries' => $combinedData,
            'redsheets' => $redsheets,
        ];
        
        return DataTables::of($allData)->make(true);

    }

    public function discharge(Request $request)
    {
            $reception = Reception::findOrFail($request->receptionId);
            $reception->exit_date = now();
            $reception->save();

            $hospitalization = new Hospitalization();
            $hospitalization->reception_id = $request->receptionId;
            $hospitalization->exit_date = now();  
            $hospitalization->hospital_discharges_id=1;
            $hospitalization->save();

            return response()->json([
                'message' => 'Paciente dado de alta.',
            ], 200);

    }
    
    public function dischargePatient(Request $request) {
        $request->validate([
            'receptionId' => 'required|exists:hospitalizations,reception_id',
            'dischargeType' => 'required|string'
        ]);
    
        $discharge = HospitalDischarge::where('name', $request->dischargeType)->first();
    
        if (!$discharge) {
            return response()->json(['message' => 'Tipo de alta no válido.'], 400);
        }
    
        $hospitalization = Hospitalization::where('reception_id', $request->receptionId)->first();
    
        if (!$hospitalization) {
            return response()->json(['message' => 'Hospitalización no encontrada.'], 404);
        }
    
        $hospitalization->update([
            'hospital_discharges_id' => $discharge->id,
            'exit_date' => now() // Asegura que se registre la fecha de salida actual
        ]);
    
        return response()->json(['message' => 'Alta registrada exitosamente.']);
    }
    
    
}
