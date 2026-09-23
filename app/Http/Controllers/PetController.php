<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetWeight;
use App\Models\Breed;
use App\Http\Requests\PetRequest;
use App\Http\Requests\FamilyRequest;
use App\Models\FamClassification;
use App\Models\Family;
use App\Models\File;
use App\Models\Genre;
use App\Models\PetClassification;
use App\Models\ReproductiveStatus;
use App\Models\Species;
use App\Services\Phone\PhoneNumberService;
use App\Exceptions\PetQuickCreateValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use libphonenumber\NumberParseException;
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

        // Si la mascota nace con un peso, ese también es su primera
        // medición registrada (ver PetWeight) — misma transacción, para no
        // dejar la mascota creada sin su historial si algo falla.
        $pet = DB::transaction(function () use ($validatedData) {
            $pet = Pet::create($validatedData);
            $this->recordWeightHistory($pet, $validatedData['weight'] ?? null);

            return $pet;
        });

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
     * Creación rápida de mascota desde el modal PetQuickCreate (recepciones).
     *
     * Resuelve la familia (existente, o nueva a crear) y crea la mascota
     * dentro de UNA sola transacción de base de datos: si la creación de la
     * mascota falla, una familia nueva creada durante este mismo intento se
     * revierte junto con ella; una familia ya existente jamás se toca (solo
     * se lee), así que nunca puede ser afectada por el rollback.
     *
     * Reutiliza las mismas reglas/mensajes de validación que FamilyRequest y
     * PetRequest usan hoy en sus endpoints individuales (families.store /
     * pets.store), para no introducir comportamiento nuevo de validación.
     */
    public function quickCreate(Request $request)
    {
        try {
            $result = DB::transaction(function () use ($request) {
                $familyMode = $request->input('family_mode');
                $family = null;

                if ($familyMode === 'new') {
                    $familyInput = $this->normalizeFamilyPhones((array) $request->input('family', []));

                    $familyRequest = new FamilyRequest();
                    $familyValidator = Validator::make(
                        $familyInput,
                        $familyRequest->rules(),
                        $familyRequest->messages(),
                        $familyRequest->attributes()
                    );

                    if ($familyValidator->fails()) {
                        throw new PetQuickCreateValidationException('family', $familyValidator);
                    }

                    // email_confirmation es solo para validar; no debe llegar al modelo
                    // (mismo criterio que FamilyController::store()).
                    $familyData = $familyValidator->validated();
                    unset($familyData['email_confirmation']);

                    $family = Family::create($familyData);
                    $familyId = $family->id;
                } else {
                    $familyId = $request->input('family_id');

                    $familyExistsValidator = Validator::make(
                        ['family_id' => $familyId],
                        ['family_id' => 'required|integer|exists:families,id'],
                        [],
                        ['family_id' => 'familia']
                    );

                    if ($familyExistsValidator->fails()) {
                        // El selector de familia existente vive en el bloque de
                        // campos de la mascota dentro del modal (QC_PET_FIELD_MAP),
                        // así que su error se reporta con ese mismo scope.
                        throw new PetQuickCreateValidationException('pet', $familyExistsValidator);
                    }
                }

                $petInput = (array) $request->input('pet', []);
                $petInput['family_id'] = $familyId;

                $petRequest = new PetRequest();
                $petRequest->replace($petInput);

                $petValidator = Validator::make($petInput, $petRequest->rules());
                $petRequest->withValidator($petValidator);

                if ($petValidator->fails()) {
                    throw new PetQuickCreateValidationException('pet', $petValidator);
                }

                $imageService = new File();
                $picture_id = $imageService->uploadFile($request, 'pets');

                $petData = $petValidator->validated();
                $petData['picture_id'] = $picture_id;

                $pet = Pet::create($petData);
                $this->recordWeightHistory($pet, $petData['weight'] ?? null);

                return [
                    'family' => $family,
                    'family_id' => $familyId,
                    'pet' => $pet,
                ];
            });
        } catch (PetQuickCreateValidationException $e) {
            return response()->json([
                'errors' => [$e->scope => $e->validator->errors()->toArray()],
            ], 422);
        }

        $pet = $result['pet'];
        $family = $result['family'];

        return response()->json([
            'success' => true,
            'family_id' => $result['family_id'],
            'family' => $family ? [
                'id' => $family->id,
                'name' => $family->name,
                'phone' => $family->phone,
            ] : null,
            'pet' => [
                'id' => $pet->id,
                'name' => $pet->name,
                'number_chip' => $pet->number_chip,
                'family_id' => $pet->family_id,
            ],
        ]);
    }

    /**
     * Normaliza phone/contact_number a E.164 igual que
     * FamilyRequest::prepareForValidation(), que no corre aquí porque el
     * payload no llega como una petición completa a families.store.
     */
    private function normalizeFamilyPhones(array $data): array
    {
        $service = app(PhoneNumberService::class);

        foreach (['phone', 'contact_number'] as $field) {
            if (blank($data[$field] ?? null)) {
                continue;
            }

            try {
                $data[$field] = $service->toE164($data[$field]);
            } catch (NumberParseException) {
                // Deja el valor original; ValidPhoneNumber lo rechazará con un mensaje claro.
            }
        }

        return $data;
    }

    /**
     * Registra una medición en el historial de peso (ver PetWeight) desde
     * cualquier punto donde se capture pets.weight fuera de una consulta
     * (form principal de mascota, quick-create desde el modal de
     * recepción): reception_id queda null a propósito — es exactamente el
     * escenario "peso fuera de consulta" ya contemplado por el historial.
     * No hace nada si $weight viene vacío.
     */
    private function recordWeightHistory(Pet $pet, ?string $weight): void
    {
        if (blank($weight)) {
            return;
        }

        PetWeight::create([
            'pet_id' => $pet->id,
            'reception_id' => null,
            'weight' => $weight,
            'measured_at' => now(),
            'created_by' => auth()->id(),
        ]);
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

        // Punto de retorno tras guardar (ver update()): solo cuando se llega
        // aquí desde el Historial Médico (pet-history.index) debe volver
        // ahí en vez del listado general de mascotas. Viaja como query
        // param, no cambia el comportamiento por defecto de esta pantalla.
        $returnTo = request()->query('return_to');

        return view('pet.edit', compact('pet', 'family', 'genders', 'ReproductiveStatuses', 'PetClassifications', 'Species', 'Breeds', 'returnTo'));
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

        $validatedData = $request->validated();
        // Se captura ANTES de update(): el peso no se edita, se registra una
        // medición nueva (ver PetWeight) solo cuando realmente cambió — si
        // el formulario se guarda sin tocar el peso, no se debe generar una
        // entrada de historial redundante.
        $previousWeight = $pet->weight;

        DB::transaction(function () use ($pet, $validatedData, $previousWeight) {
            $pet->update($validatedData);

            $newWeight = $validatedData['weight'] ?? null;
            if (!blank($newWeight) && $newWeight !== $previousWeight) {
                $this->recordWeightHistory($pet, $newWeight);
            }
        });

        // Si se llegó a este formulario desde el Historial Médico (ver
        // pet.form -> input hidden "return_to", sembrado por edit()),
        // regresa ahí en vez del listado general — sin afectar ningún otro
        // punto de entrada a esta pantalla.
        if ($request->input('return_to') === 'medical_history') {
            return redirect()->route('pet-history.index', $pet->id)
                ->with('success', 'Mascota actualizada exitosamente');
        }

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
