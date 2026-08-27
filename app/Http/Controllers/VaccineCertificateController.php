<?php

namespace App\Http\Controllers;

use App\Models\VaccineCertificate;
use App\Http\Requests\VaccineCertificateRequest;
use App\Models\Appointment;
use App\Models\ControlDate;
use App\Models\Pet;
use App\Models\Reception;
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
    //  public function store(VaccineCertificateRequest $request)
    //  {
    //      $this->authorize("create", VaccineCertificate::class);
    //      $vaccine = VaccineCertificate::create($request->validated());

    //      $reception = Reception::find($request->reception_id);
    //      $next_application_date = $request->next_application_date;
    //      $time_next_check = '08:00:00';

    //       // Concatenar la fecha y la hora para lograr el formato de tipo datetime
    //       $datetime = $next_application_date . ' ' . $time_next_check;

    //      ControlDate::create([
    //          'reception_id' => $request->reception_id,
    //          'pet_id' => $reception ? $reception->pet_id : null,
    //          'family_id' => $reception ? $reception->family_id : null,
    //          'date_type_id' => 3,
    //          'status_date_id' => 1,
    //          'user_id' => $request->vet_id,
    //          'date' => $datetime,
    //      ]);

    //      return response()->json($vaccine);
    //  }

//      public function store(VaccineCertificateRequest $request)
//  {
//      $this->authorize("create", VaccineCertificate::class);
//      $vaccine = VaccineCertificate::create($request->validated());

//      $reception = Reception::find($request->reception_id);
//      $next_application_date = $request->next_application_date;
//      $time_next_check = '08:00:00';
//      $datetime = $next_application_date . ' ' . $time_next_check;
//      // tipo de cita en base al seeder
//      $dateTypeMap = [
//          1 => 3,
//          2 => 10,
//          3 => 11,
//      ];

//      $date_type_id = $dateTypeMap[$vaccine->service_id] ?? null;
 
//      if ($date_type_id !== null) {
//          ControlDate::createIfNotDuplicate([
//              'reception_id' => $request->reception_id,
//              'pet_id' => $reception ? $reception->pet_id : null,
//              'family_id' => $reception ? $reception->family_id : null,
//              'date_type_id' => $date_type_id,
//              'status_date_id' => 1,
//              'user_id' => $request->vet_id,
//              'date' => $datetime,
//          ]);
//      }

//      return response()->json($vaccine);
//  }
 public function store(VaccineCertificateRequest $request)
{
    $this->authorize("create", VaccineCertificate::class);

    $reception = Reception::find($request->reception_id);

    // tipo de cita en base al seeder
    $dateTypeMap = [
        1 => 3,
        2 => 10,
        3 => 11,
    ];

    $time_next_check = '08:00:00';

    $vaccines = collect();

    foreach ($request->aplicaciones as $aplicacion) {
        $data = array_merge($aplicacion, [
            'pet_id'        => $request->pet_id,
            'vet_id'        => $request->vet_id,
            'reception_id'  => $request->reception_id,
        ]);

        $vaccine = VaccineCertificate::create($data);
        $vaccines->push($vaccine);

        $date_type_id = $dateTypeMap[$vaccine->service_id] ?? null;

        if ($date_type_id !== null && $vaccine->next_application_date) {
            $datetime = $vaccine->next_application_date . ' ' . $time_next_check;

            ControlDate::createIfNotDuplicate([
                'reception_id'   => $request->reception_id,
                'pet_id'         => $reception?->pet_id,
                'family_id'      => $reception?->family_id,
                'date_type_id'   => $date_type_id,
                'status_date_id' => 1,
                'user_id'        => $request->vet_id,
                'date'           => $datetime,
            ]);
        }
    }

    return response()->json($vaccines);
}


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pet = Pet::with('family')->find($id);
        $vaccineCertificates = VaccineCertificate::with("pet", "microsip")
            ->where("pet_id", $id)
            ->orderBy("application_date", "asc")
            ->get();
        $vaccineCertificate = new VaccineCertificate();
        // $this->authorize("viewAny", VaccineCertificate::class);
        return view('vaccine-certificate.show', compact('vaccineCertificates', 'pet', 'vaccineCertificate'));
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
        $pet = Pet::with('family', 'genre')->find($id);
        $certificate = VaccineCertificate::with("pet", "microsip")
            ->where("pet_id", $id)
            ->orderBy("application_date", "asc")
            ->get();
        $pdf = Pdf::loadView("vaccine-certificate.pdf", compact("certificate", "pet"));
        return $pdf->stream("PDF.pdf");
    }

    public function list()
    {
        $vaccineCertificate = VaccineCertificate::with('pet')->get();
        return DataTables::of($vaccineCertificate)->make(true);
    }
}
