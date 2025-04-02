<?php

namespace App\Http\Controllers;

use App\Models\GeneralGrooming;
use App\Http\Requests\GeneralGroomingRequest;
use App\Models\ControlDate;
use App\Models\Grooming;
use App\Models\Reception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf  as Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Class GeneralGroomingController
 * @package App\Http\Controllers
 */
class GeneralGroomingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $generalGroomings = GeneralGrooming::paginate();

        return view('general-grooming.index', compact('generalGroomings'))
            ->with('i', (request()->input('page', 1) - 1) * $generalGroomings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $generalGrooming = new GeneralGrooming();
        $reception = Reception::findOrFail(23);
        return view('general-grooming.create', compact('generalGrooming','reception'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GeneralGroomingRequest $request)
    {
        // $date = $request->created_at;
        $newFolio = $this->folio()->getData()->folio;
        
        $data = $request->validated();
        $data['folio'] = $newFolio;
        $new = GeneralGrooming::create($data);

        $reception = Reception::find($request->reception_id);

        ControlDate::create([
            'reception_id' => $request->reception_id,
            'pet_id' => $reception ? $reception->pet_id : null,
            'family_id' => $reception ? $reception->family_id : null,
            'date_type_id' => 9,
            'status_date_id' => 1,
            'user_id' => auth()->id(),
            'date' => $request->next_service,
        ]);



        return response()->json($new);
        // return redirect()->route('general-groomings.index')
        //     ->with('success', 'GeneralGrooming created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $generalGrooming = GeneralGrooming::find($id);

        return view('general-grooming.show', compact('generalGrooming'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $generalGrooming = GeneralGrooming::find($id);

        return view('general-grooming.edit', compact('generalGrooming'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GeneralGroomingRequest $request, GeneralGrooming $generalGrooming)
    {
        $generalGrooming->update($request->validated());

        return redirect()->route('general-groomings.index')
            ->with('success', 'GeneralGrooming updated successfully');
    }

    public function destroy($id)
    {
        GeneralGrooming::find($id)->delete();

        return redirect()->route('general-groomings.index')
            ->with('success', 'GeneralGrooming deleted successfully');
    }

    public function folio()
    {
        // $date = Carbon::parse($date)->toDateString();
        $lastRecord = GeneralGrooming::orderBy('folio', 'desc')
            ->first();

        $newFolio = $lastRecord ? (($lastRecord->folio % 200) + 1) : 1;

        return response()->json(['folio' => $newFolio]);
    }

    public function criticsign(int $id)
    {
        $general = GeneralGrooming::where('reception_id', $id)->get()->first();

        return view('grooming.critic-status', compact("general"));
    }

    public function criticpdf(Request $request, int $id)
    {
        
        $general = GeneralGrooming::findOrFail($id);
        $signatureDataUrl = $request->input('signature');
        $name = $request->input('name');

        $pdf = PDF::loadView('grooming.critic-status', [
            'general' => $general,
            'signatureDataUrl' => $signatureDataUrl,
            'name' => $name,
            'isPdf' => true
        ]);

        $pdfPath = '/groomings/critic-statuses/responsive_' . $id . '.pdf';
        Storage::put('public' . $pdfPath, $pdf->output());

        // $format = new Format();
        // $format->format_type_id = 4;
        // $format->reception_id = $id;
        // $format->pet_id = $reception->pet->id;
        // $format->format_pdf = $pdfPath;
        // $format->save();

        $pdfUrl = Storage::url($pdfPath);

        return response()->json(['url' => asset('storage' . $pdfPath)]);
    }

    public function deliverytest(int $id)
    {
        $reception = Reception::with('payment', 'pet', 'family', 'grooming')->find($id);

        $pdf = Pdf::loadView("grooming.delivery-pdf", compact("reception"))
                ->setPaper('B6', 'landscape');

        return $pdf->stream("PDF.pdf");
    }

    public function deliverydata(int $id)
    {
        $reception = Reception::with('payment', 'pet', 'pet.genre', 'pet.petClassification', 'family', 'grooming')->find($id);

        return response()->json($reception);
    }
}
