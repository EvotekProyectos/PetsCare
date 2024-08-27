<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\AdmissionTypeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReceptionTypeController;
use App\Http\Controllers\AttentionStatusController;
use App\Http\Controllers\ReproductiveStatusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::group(['middleware' => ['auth']], function () {

    // USERS
    Route::get('/users/list', [UserController::class, 'list'])->name('users.list');
    Route::get('/users/permissions/{id?}', [UserController::class, 'getPermissionsForUsers'])->name('users.permissions');
    Route::post('/users/changePermissions/{id?}', [UserController::class, 'changePermissions'])->name('users.changePermissions');
    Route::resource('/users', UserController::class);

    // REASONS
    Route::get('/reasons/list', [ReasonController::class, 'list'])->name('reasons.list');
    Route::resource('/reasons', ReasonController::class);

    // AREAS
    Route::get('/areas/list', [AreaController::class, 'list'])->name('areas.list');
    Route::resource('areas', AreaController::class);

    // ATTENTION STATUSES
    Route::get('/attention-statuses/list', [AttentionStatusController::class, 'list'])->name('attention-statuses.list');
    Route::resource('attention-statuses', AttentionStatusController::class);

    // RECEPTION TYPES
    Route::get('/reception-types/list', [ReceptionTypeController::class, 'list'])->name('reception-types.list');
    Route::resource('reception-types', ReceptionTypeController::class);

    // ADMISSION TYPES
    Route::get('/admission-types/list', [AdmissionTypeController::class, 'list'])->name('admission-types.list');
    Route::resource('admission-types', AdmissionTypeController::class);

    // LOGS
    Route::get('/logs/list', [LogController::class, 'list'])->name('logs.list');
    Route::resource('/logs', LogController::class);

    //Rooms
    Route::get('/rooms/list', [RoomController::class, 'list'])->name('rooms.list');
    Route::resource('rooms', RoomController::class);

    //Genres
    Route::get('/genres/list', [GenreController::class, 'list'])->name('genres.list');
    Route::resource('genres', GenreController::class);
    
    //Reproductive Statuses
    Route::get('/reproductive-statuses/list', [ReproductiveStatusController::class, 'list'])->name('reproductive-statuses.list');
    Route::resource('reproductive-statuses', ReproductiveStatusController::class);
});
