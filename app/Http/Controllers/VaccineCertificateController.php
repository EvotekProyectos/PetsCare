<?php

namespace App\Http\Controllers;

use App\Models\VaccineCertificate;
use App\Http\Requests\VaccineCertificateRequest;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class VaccineCertificateController
 * @package App\Http\Controllers
 */
class VaccineCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaccineCertificates = VaccineCertificate::paginate();
        $this->authorize("viewAny", VaccineCertificate::class);
        return view('vaccine-certificate.index', compact('vaccineCertificates'))
            ->with('i', (request()->input('page', 1) - 1) * $vaccineCertificates->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vaccineCertificate = new VaccineCertificate();
        $this->authorize("create", VaccineCertificate::class);
        return view('vaccine-certificate.create', compact('vaccineCertificate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VaccineCertificateRequest $request)
    {
        $this->authorize("create", VaccineCertificate::class);
        $vaccine = VaccineCertificate::create($request->validated());

        return response()->json($vaccine);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pet=Pet::with('family')->find($id);
        $vaccineCertificates = VaccineCertificate::with("pet")->where("pet_id", $id)->get();
        // $this->authorize("viewAny", VaccineCertificate::class);
        return view('vaccine-certificate.show', compact('vaccineCertificates', 'pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vaccineCertificate = VaccineCertificate::find($id);
        $this->authorize("update", $vaccineCertificate);
        return view('vaccine-certificate.edit', compact('vaccineCertificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VaccineCertificateRequest $request, VaccineCertificate $vaccineCertificate)
    {
        $vaccineCertificate->update($request->validated());
        $this->authorize("update", $vaccineCertificate);
        return redirect()->route('vaccine-certificates.index')
            ->with('success', 'VaccineCertificate updated successfully');
    }

    public function destroy($id)
    {
        $vaccineCertificate = VaccineCertificate::find($id); 
        $this->authorize("delete", $vaccineCertificate);
        $vaccineCertificate->delete();
        return response()->json($vaccineCertificate);
    }
    
    public function imprimir(int $id)
    {   
        $pet=Pet::with('family','genre')->find($id);
        $certificate = VaccineCertificate::with("pet")->where("pet_id", $id)->get();
        $pdf = Pdf::loadView("vaccine-certificate.pdf", compact("certificate", "pet"));
        return $pdf->stream("PDF.pdf");
        
    }

    public function list()
    {
        $vaccineCertificate = VaccineCertificate::with('pet')->get();
        return DataTables::of($vaccineCertificate) ->make(true);
    }
}
