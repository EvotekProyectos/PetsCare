<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use App\Http\Requests\ReceptionRequest;
use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Family;
use App\Models\Format;
use App\Models\GeneralGrooming;
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
        $this->authorize("viewAny", Reception::class); //valida permiso de ver recepciones

        return view('reception.index',); //regresa la vista 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //recopila los catalogos necesarios par que el form funcione
        $reception = new Reception();
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::where("deceased", 0)->get(); //solo trae mascotas que no estn marcadas como fallecidas

        $this->authorize("create", Reception::class); //valida el permiso para crear recepciones
        return view('reception.create', compact('reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms', 'pets')); // Regresa la vista con los catalogos correspondienes 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReceptionRequest $request)
    {
        $this->authorize("create", Reception::class); //valida permiso de crear recepciones
        $reception = Reception::create($request->validated()); //manda los daos a validar y crea el registro

        if ($request->reception_type_id == 1) { //cuando es una recepcion de consulta, le hace un registro al historial de atención poniendole un status
            ReceptionStatusHistory::create([
                'reception_id' => $reception->id,
                'attention_status_id' => 2,
            ]);
        }

        if ($request->reception_type_id == 2) {  //en los casos de recepcion d ehospital redirige la respuea an idex de hospitalizacion
            return redirect()->route('hospital.list', ['id' => $reception->id])
                ->with('success', 'Recepción de hospitalización guardada exitosamente.');
        } elseif ($request->reception_type_id == 5) { //en caso de ser cremación redirigue a seguir llenado el form para la cremacion
            return redirect()->route('new.cremation', ['id' => $reception->id])
                ->with('success', 'Recepción de cremación guardada exitosamente.');
        }

        if ($request->reception_type_id == 3) { //en los casos de recepcion de grooming hace un registro de historial de atencion en esa tabla
            GroomingStatusHistory::create([
                'reception_id' => $reception->id,
                'grooming_status_id' => 1,
            ]);
            return redirect()->route('receptions.grooming',  $reception->id); //redirigue a seguir llenado el form necesario para el grooming
        }


        if ($request->reception_type_id == 4) { //en los casos de recpciion tipo hotel redirigue a seguir llenado el formulario de hotel 
            return redirect()->route('hotel.create', ['id' => $reception->id]);
            //->with('success', 'Recepción de hotel guardada exitosamente.');
        }

        return redirect()->route('receptions.index')
            ->with('success', 'Recepción guardada exitosamente.'); //el caso generico redigirue al index general de las recepciones 
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
        //recopila los catalogos para el form
        $reception = Reception::find($id);
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::where("deceased", 0)->get(); //filtra a mascotas no fallecidas
        $this->authorize("update", $reception); //valida el permiso de edicion
        return view('reception.edit', compact('reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms', 'pets')); //redirigue a la pantalla junto a todos los catalogos
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReceptionRequest $request, Reception $reception)
    {
        $this->authorize("update", $reception); //valida el permiso de editar
        $reception->update($request->validated()); //valida los datos y actualiza el registro 
        return redirect()->route('receptions.index')
            ->with('success', 'Recepción actualizada exitósamente.'); //redirge al index 
    }

    public function destroy($id)
    {
        $reception = Reception::find($id); //ecuentra que existe el registro a eliminar
        $this->authorize("delete", $reception); //valida el permiso para eliminar
        $reception->delete(); //elimina el registro

        return response()->json($reception); //regresa el mensje
    }

    public function list(int $reception_type_id)
    {
        //Recopilamos los registros de recepcion junto a todas las relaciones necesarias 
        $receptions = Reception::with(
            'admissionType',
            'area',
            'family',
            'pet',
            'reason',
            'receptionist',
            'receptionType',
            'room',
            'vet',
        )
            ->where('reception_type_id', $reception_type_id) //filtramos el tipo de recepcion de acuerdo al id recibido en la funcion
            ->get();
        return DataTables::of($receptions)->make(true); //regresamos como datatable
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
        //se recopilan todas las recpciones de la mascota 
        $receptions = Reception::with('receptionType', 'reason', 'vet')->where('pet_id', $id)->get();

        //Pendiente de determinar
        // $redSheets = RedSheet::whereHas('reception', function ($query) use ($id) {
        //     $query->where('pet_id', $id);
        // })->with('reception')->get();

        // $surgeries = Surgery::whereHas('reception', function ($query) use ($id) {
        //     $query->where('pet_id', $id);
        // })->with('reception')->get();

        // $prescriptions = Prescription::where('pet_id', $id)->get();

        // $data = [];
        // foreach ($receptions as $reception) {
        //     $data[] = [
        //         'entry_date' => $reception->entry_date,
        //         'vet_name' => $reception->vet->name ?? '',
        //         'reception_type' => $reception->receptionType->name ?? '',
        //         'reason' => $reception->reason->name ?? '',
        //         'reception_id' => $reception->id,
        //         'pet_id' => $reception->pet_id,
        //         'redSheets' => $redSheets->pluck('description')->toArray(),
        //         'surgeries' => $surgeries->pluck('surgery_type')->toArray(),
        //         'prescriptions' => $prescriptions->pluck('medicine')->toArray(),
        //     ];
        // }

        return DataTables::of($receptions)->make(true); //se regresa para datable la info
    }



    public function hospital_authorization($id)
    {
        $reception = Reception::find($id); //buscamos la recepcion correspondiente
        return view('reception.pdf', compact("reception")); //enviamos los datos a la vista del pfd para firma y llendo
    }

    public function hospital_authorizationpdf(Request $request, $id)
    {
        $reception = Reception::find($id); //buscamos la recepcion correspondiente
        $pet = Pet::with('family', 'genre')->find($reception->pet_id); //buscamos la mascota correspondiente
        $total = $request->input('total'); // recuperamos el total enviado
        $signatureDataUrl = $request->input('signature'); //recuperamos la firma del cliente 

        // Generar el PDF con los datos de la recepción,  mascota y llenado del propietario
        $pdf = PDF::loadView('reception.pdf', [
            'reception' => $reception,
            'pet' => $pet,
            'total' => $total,
            'signatureDataUrl' => $signatureDataUrl,
            'isPdf' => true
        ]);

        // Guardar el PDF en el almacenamiento público
        $pdfPath = '/receptions/reception_' . $id . '.pdf';
        Storage::put('public' . $pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath); // Obtener la URL pública del PDF

        $format = new Format();
        $format->format_type_id = 1; // Tipo de formato: autorización hospitalaria
        $format->reception_id = $id; // Relacionado con la recepción
        $format->pet_id = $pet->id; // Relacionado con la mascota
        $format->format_pdf = $pdfPath; // Ruta del PDF almacenado
        $format->save();

        // Retornar la respuesta JSON con la URL del PDF y el ID del formato generado
        return response()->json(['url' => asset('storage' . $pdfPath), 'format_id' => $format->id]);
    }


    public function getReceptionArea($id)
    {
        $reception = Reception::find($id); //busca recepcion correpondinete 
        return response()->json(['area_id' => $reception->area_id]); //regresa el area que tiene registrada la recepcion
    }


    public function getFamilyByPet($pet_id)
    {
        $pet = Pet::find($pet_id); //encuentra a la mascota
        if ($pet && $pet->family) {
            return response()->json($pet->family); //cuando encuentra a la mascota y la relacion de familia, regrea la info
        }
        return response()->json(null, 404);
    }

    public function transfer(Request $request, $id)
    {
        $reception = Reception::findOrFail($id); // Buscar la recepción
        $this->authorize("update", $reception); //valida permisos de editrae
        $reception->update($request->validate([
            'admission_type_id' => 'integer|exists:admission_types,id',
        ]));  // Validar y actualizar el tipo de admisión

        return response()->json($reception); // Retornar la recepción actualizada en formato JSON
    }

    public function cuenta($id)
    {
        $reception = Reception::with('pet')->where('id', $id)->first();  // Obtener la recepción con su mascota asociada
        $redSheets = RedSheet::where('reception_id', $id)->with('imaging', 'lab', 'service')->get(); // Obtener hojas rojas relacionadas con imagenología, laboratorio y servicios
        $surgeries = Surgery::where('reception_id', $id)->with('service')->get(); // Obtener cirugías asociadas con sus servicios

        // Construir el arreglo de datos
        $data = [
            'reception' => $reception,
            'redSheets' => $redSheets,
            'surgeries' => $surgeries,
        ];
        return DataTables::of($data)->make(true); // Retornar los datos formateados para DataTables
    }


    public function groomingservice(int $id)
    {
         // Crear instancias vacías de Grooming y GeneralGrooming y recopila catalogos necesarios
        $grooming = new Grooming();
        $generalGrooming = new GeneralGrooming();
        $products = Producto::where("ESTATUS",  "A")->get();
        $reception = Reception::find($id);
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::all();

        $this->authorize("create", Grooming::class); //verifica permisos para crear groomings
         // Retornar la vista con los datos necesario
        return view('grooming.create', compact('grooming', 'products', 'reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms', 'pets', 'generalGrooming'));
    }
}
