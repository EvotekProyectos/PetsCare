<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Breed;
use App\Http\Requests\PetRequest;
use App\Models\FamClassification;
use App\Models\Family;
use App\Models\File;
use App\Models\Genre;
use App\Models\PetClassification;
use App\Models\ReproductiveStatus;
use App\Models\Species;
use PhpParser\Node\Expr\FuncCall;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class PetController
 * @package App\Http\Controllers
 */
class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = Pet::paginate();

        return view('pet.index', compact('pets'))
            ->with('i', (request()->input('page', 1) - 1) * $pets->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pet = new Pet();
        $family = new Family();
        $genders = Genre::all();
        $ReproductiveStatuses = ReproductiveStatus::all();
        $PetClassifications = PetClassification::all();
        $Species = Species::where('active', true)->orderBy('name')->get();
        $Breeds = collect();

        return view('pet.create', compact('pet', 'family', 'genders', 'ReproductiveStatuses', 'PetClassifications', 'Species', 'Breeds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PetRequest $request)
    {
        $imageService = new File();

        $picture_id = $imageService->uploadFile($request, "pets");

        if ($picture_id) {
            $request->merge(['picture_id' => $picture_id]);
        }

        $validatedData = $request->validated();
        $validatedData['picture_id'] = $picture_id;

        $pet = Pet::create($validatedData);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'id' => $pet->id,
                'name' => $pet->name,
                'number_chip' => $pet->number_chip,
                'family_id' => $pet->family_id,
            ]);
        }

        $id = $pet->family_id;

        return redirect()->route('families.edit', $id)
            ->with('success', 'Mascota guardada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pet = Pet::find($id);

        return view('pet.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pet = Pet::with('file')->find($id);
        $fam_id = $pet->family_id;
        $family = Family::where("id", $fam_id)->first();
        $genders = Genre::all();
        $ReproductiveStatuses = ReproductiveStatus::all();
        $PetClassifications = PetClassification::all();
        $Species = Species::where('active', true)->orderBy('name')->get();
        // Razas de la especie actual del pet
        $Breeds = $pet->species_id
            ? Breed::where('species_id', $pet->species_id)->where('active', true)->orderBy('name')->get()
            : collect();

        return view('pet.edit', compact('pet', 'family', 'genders', 'ReproductiveStatuses', 'PetClassifications', 'Species', 'Breeds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetRequest $request, Pet $pet)
    {
        if ($request->hasFile('file')) {
            if ($pet->picture_id) {
                $file = File::find($pet->picture_id);
                if ($file) {
                    $newPictureId = $file->updateFile($request, 'pets');
                }
            } else {
                $imageService = new File();

                $newPictureId= $imageService->uploadFile($request, "pets");
            }

            if ($newPictureId) {
                $pet->picture_id = $newPictureId;
            }
        }

        $pet->update($request->validated());

        return redirect()->route('families.index')
            ->with('success', 'Mascota actualizada exitosamente');
    }

    public function destroy($id)
    {
        $pet = Pet::find($id);
        $pet->delete();

        return response()->json($pet);
    }

    public function list()
    {
        $pets = Pet::with("family", "genre", "petClassification", "file", "reproductiveStatus")
            ->get();

        return DataTables::of("$pets")->make(true);
    }

   
     public function preview($family)
     {
         $pets = Pet::with("file")
             ->where("family_id", $family)->get();

         return response()->json($pets);
     }

     public function data($family)
     {
        $pets = Pet::where("deceased", 0)
             ->where("family_id", $family)->get();

         return response()->json($pets);
     }



}
