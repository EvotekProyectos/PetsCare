<?php

use App\Models\ControlDate;
use App\Models\Family;
use App\Models\Grooming;
use App\Models\Hotel;
use App\Models\Prescription;
use App\Models\Reception;
use App\Models\RedSheet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $family = Family::with('pets')->where('email', $request->email)->first();

    if (!$family) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    // Generar código de verificación
    $code = rand(100000, 999999);

    // Guardar código temporalmente (válido por 5 minutos)
    Cache::put("login_code_{$family->email}", $code, now()->addMinutes(5));

    return response()->json([
        'message' => 'Código de verificación enviado (modo prueba)',
        'email' => $family->email,
        'code' => $code,
        'family' => $family,
        'pets' => $family->pets,
    ]);
});

Route::post('/verify-code', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|numeric',
    ]);

    $storedCode = Cache::get("login_code_{$request->email}");

    if ($storedCode != $request->code) {
        return response()->json(['message' => 'Código incorrecto'], 401);
    }

    // Aquí podrías limpiar el código si quieres que sea de un solo uso
    Cache::forget("login_code_{$request->email}");

    return response()->json(['message' => 'Código verificado']);
});


Route::get('/last-prescription/{petId}', function ($petId) {
    $lastPrescription = Prescription::with('vet')
        ->where('pet_id', $petId)
        ->latest()
        ->first();

    if (!$lastPrescription) {
        return response()->json(['message' => 'Sin prescripciones encontradas'], 404);
    }

    return response()->json($lastPrescription);
});


Route::get('/next-appointment/{petId}', function ($petId) {
    $nextAppointment = ControlDate::with('user')
        ->where('pet_id', $petId)
        ->where('date', '>', now()) // Opcional: solo futuras
        ->orderBy('date') // Más próxima en el futuro
        ->first();

    if (!$nextAppointment) {
        return response()->json(['message' => 'Sin próximas citas encontradas'], 404);
    }

    return response()->json($nextAppointment);
});

// Route para obtener la información de hospitalización
Route::get('/hospital/{petId}', function ($petId) {
    // Obtener la última recepción con reception_type_id = 2 (hospitalización)
    $reception = Reception::where('pet_id', $petId)
                          ->where('reception_type_id', 2)  // Filtrar por hospitalización
                          ->orderBy('created_at', 'desc')  // Obtener la última recepción
                          ->first();

    if (!$reception) {
        return response()->json(['message' => 'Hospitalización no encontrada'], 404);
    }

    // Obtener las hojas de servicio asociadas (red_sheets)
    $redSheets = RedSheet::with('serv', 'laboratory', 'img')->where('reception_id', $reception->id)
                         ->orderBy('day_count')
                         ->get();

    return response()->json([
        'reception' => $reception,
        'red_sheets' => $redSheets
    ]);
});

Route::get('/grooming/{petId}', function ($petId) {
    $grooming = Grooming::with([
        'reception',
        'serv',
        'statusHistories.groomingStatus'
    ])
    ->whereHas('reception', function ($query) use ($petId) {
        $query->where('pet_id', $petId);
    })
    ->latest()
    ->first();

    if (!$grooming) {
        return response()->json(['message' => 'No grooming data found'], 404);
    }

    $latestStatus = $grooming->statusHistories->sortByDesc('created_at')->first();

    return response()->json([
        'entry_time' => $grooming->reception->entry_date,
        'service' => $grooming->serv->NOMBRE ?? 'Sin servicio',
        'status' => $latestStatus ? $latestStatus->groomingStatus->name : 'Sin estatus',
        'status_time' => $latestStatus ? $latestStatus->created_at : null,
        'exit_date'=> $grooming->reception->exit_date,
    ]);
});

Route::get('/hotel/{petId}', function ($petId) {
    // Traemos el servicio de hotel relacionado con el petId
    $hotel = Hotel::with([
        'reception',
        'serv', // Suponiendo que service_type_id se relaciona con un modelo 'ServiceType'
    ])
    ->whereHas('reception', function ($query) use ($petId) {
        $query->where('pet_id', $petId);
    })
    ->latest()
    ->first();

    if (!$hotel) {
        return response()->json(['message' => 'No hotel data found'], 404);
    }

    return response()->json([
        'reception_id' => $hotel->reception->id,
         'entry_date' => $hotel->reception->entry_date,
          'exit_date' => $hotel->reception->exit_date,
        'service_type' => $hotel->serv->NOMBRE ?? 'Sin tipo de servicio',
        'video' => $hotel->video,
        'number_of_days' => $hotel->number_days,
    ]);
});
