<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Http\Requests\FamilyRequest;
use App\Models\FamClassification;
use App\Models\Genre;
use App\Models\Pet;
use App\Models\PetClassification;
use App\Models\ReproductiveStatus;
use App\Models\Room;
use App\Models\Species;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class FamilyController
 * @package App\Http\Controllers
 */
class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $families = Family::paginate();
        $this->authorize("viewAny", Family::class);

        return view('family.index', compact('families'))
            ->with('i', (request()->input('page', 1) - 1) * $families->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $family = new Family();
        $FamClassifications = FamClassification::all();
        $this->authorize("create", Family::class);

        return view('family.create', compact('family', 'FamClassifications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FamilyRequest $request)
    {
        // email_confirmation es solo para validar; no debe llegar al modelo.
        $family = Family::create($request->safe()->except(['email_confirmation']));
        $this->authorize("create", Family::class);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'id' => $family->id,
                'name' => $family->name,
                'phone' => $family->phone,
            ]);
        }

        $id = $family->id;

        return redirect()->route('families.edit', $id)
            ->with('success', 'Familia guardada, ahora puedes agregar sus mascotas.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $family = Family::find($id);
        $this->authorize("view", Family::class);

        return view('family.show', compact('family'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $family = Family::find($id);
        $FamClassifications = FamClassification::all();
        $pet = new Pet();
        $genders = Genre::all();
        $ReproductiveStatuses = ReproductiveStatus::all();
        $PetClassifications = PetClassification::all();
        $Species = Species::where('active', true)->orderBy('name')->get();
        $Breeds = collect();
        $this->authorize("update", $family);

        return view('family.edit', compact('family', 'FamClassifications', 'pet', 'genders', 'ReproductiveStatuses', 'PetClassifications', 'Species', 'Breeds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FamilyRequest $request, Family $family)
    {
        // email_confirmation es solo para validar; no debe llegar al modelo.
        $family->update($request->safe()->except(['email_confirmation']));
        $this->authorize("update", $family);

        return redirect()->route('families.index')
            ->with('success', 'Family actualizada exitosamente');
    }

    public function destroy($id)
    {
        $family =Family::find($id);
        $this->authorize("delete", $family);
        $family->delete();

        return response()->json($family);
    }

    public function list()
    {
        $family = Family::with("famClassification", "pets")->get();

        return DataTables::of($family) ->make(true);
    }

    // Controlador para obtener datos de la familia y mascotas según ID de familia
public function getFamilyData($family_id)
{
    $family = Family::with('pets')->find($family_id);
    return response()->json([
        'family' => $family,
        'pets' => $family->pets,
    ]);
}

// Controlador para obtener datos de la familia y mascotas según número de teléfono
public function getPhoneData($phone)
{
    $family = Family::where('phone', $phone)->with('pets')->first();
    return response()->json([
        'family' => $family,
        'pets' => $family->pets,
    ]);
}

// Controlador para obtener datos de la familia y mascota según ID de mascota
public function getPetData($pet_id)
{
    $pet = Pet::find($pet_id);
    $family = $pet->family;
    $pets = $family->pets;
    return response()->json([
        'family' => $family,
        'pets' => $pets,
    ]);
}
}
