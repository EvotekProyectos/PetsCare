<?php

namespace App\Http\Controllers;

use App\Models\Grooming;
use App\Http\Requests\GroomingRequest;
use App\Models\GroomingStatusHistory;
use App\Models\Producto;
use App\Models\Reception;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class GroomingController
 * @package App\Http\Controllers
 */
class GroomingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groomings = Grooming::paginate();
        $this->authorize("viewAny", Grooming::class);

        return view('grooming.index', compact('groomings'))
            ->with('i', (request()->input('page', 1) - 1) * $groomings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grooming = new Grooming();
        $products = Producto::where("ESTATUS",  "A")->get();

        $this->authorize("create", Grooming::class);
        return view('grooming.create', compact('grooming', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroomingRequest $request)
    {
        $this->authorize("create", Grooming::class);
        $new = Grooming::create($request->validated());

        return response()->json($new);

        // return redirect()->route('groomings.index')
        //     ->with('success', 'Grooming created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $grooming = Grooming::find($id);
        $reception = Reception::with('pet', 'admissionType', 'area', 'statusGrooming.groomingStatus')->findorfail($id);
        // $this->authorize("view",$grooming);

        return view('grooming.show', compact('reception'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $grooming = Grooming::find($id);
        $this->authorize("update", $grooming);

        return view('grooming.edit', compact('grooming'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroomingRequest $request, Grooming $grooming)
    {
        $this->authorize("update", $grooming);
        $grooming->update($request->validated());

        return redirect()->route('groomings.index')
            ->with('success', 'Grooming updated successfully');
    }

    public function destroy($id)
    {
        $grooming = Grooming::find($id);
        $this->authorize("delete", $grooming);
        $grooming->delete();

        return response()->json($grooming);
    }

    public function list(int $id)
    {

        $groomings = Grooming::with('service', 'serv')->where('reception_id', $id)->get();

        return DataTables::of($groomings)->make(true);
    }

    public function status(Request $request)
    {
        $validatedData = $request->validate([
            'reception_id' => 'required|exists:receptions,id',
            'grooming_status_id' => 'required|exists:grooming_statuses,id',
        ]);

        try {
            $new = GroomingStatusHistory::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $new,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function groomingsign(int $id)
    {
        $reception = Reception::find($id);
        $groomings = Grooming::with('service', 'serv')->where('reception_id', $id)->get();

        return view('grooming.pdf', compact("reception", "groomings"));
    }

    public function groomingpdf(Request $request, int $id)
    {
        $reception = Reception::find($id);
        $groomings = Grooming::with('service', 'serv')->where('reception_id', $id)->get();
        $signatureDataUrl = $request->input('signature');

        $pdf = PDF::loadView('grooming.pdf', [
            'reception' => $reception,
            'groomings' => $groomings,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);

        $pdfPath = '/groomings/grooming_' . $id . '.pdf';
        Storage::put('public' . $pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        return response()->json(['url' => asset('storage' . $pdfPath)]);
    }

    public function generatePdf(int $id)
    {
        $reception = Reception::find($id);

        $groomings = Grooming::with('service', 'serv')->where('reception_id', $id)->get();

        $pdf = Pdf::loadView("grooming.pdf", compact("reception", "groomings"));

        return $pdf->stream("PDF.pdf");
    }
}
