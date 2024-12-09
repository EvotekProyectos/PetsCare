<?php

use App\Models\Reception;
use App\Models\Assignment;
use App\Models\PetHistory;
use App\Models\Appointment;
use Dotenv\Store\FileStore;
use App\Models\Prescription;
use FontLib\Table\Type\name;
use App\Models\VaccineCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\ReceptionStatusHistory;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\RedSheetController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CoverAreaController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\PetHistoryController;
use App\Http\Controllers\PetsStatusController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\AdmissionTypeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReceptionTypeController;
use App\Http\Controllers\AttentionStatusController;
use App\Http\Controllers\HospitalizationController;
use App\Http\Controllers\FamClassificationController;
use App\Http\Controllers\FormatController;
use App\Http\Controllers\FormatTypeController;
use App\Http\Controllers\HospitalDischargeController;;
use App\Http\Controllers\PetClassificationController;
use App\Http\Controllers\RoleHasPermissionController;
use App\Http\Controllers\AppointmentServiceController;
use App\Http\Controllers\FollowupInternController;
use App\Http\Controllers\FollowupSurgicalController;
use App\Http\Controllers\ReproductiveStatusController;
use App\Http\Controllers\VaccineCertificateController;
use App\Http\Controllers\ProductClassificationController;
use App\Http\Controllers\ReceptionStatusHistoryController;
use App\Models\Surgery;

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
Route::get('/megamenu', [App\Http\Controllers\HomeController::class, 'megamenu'])->name('megamenu');


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

    // FILES
    Route::resource('/files', FileController::class);

    //Rooms
    Route::get('/rooms/list', [RoomController::class, 'list'])->name('rooms.list');
    Route::resource('rooms', RoomController::class);

    //Genres
    Route::get('/genres/list', [GenreController::class, 'list'])->name('genres.list');
    Route::resource('genres', GenreController::class);

    //Reproductive Statuses
    Route::get('/reproductive-statuses/list', [ReproductiveStatusController::class, 'list'])->name('reproductive-statuses.list');
    Route::resource('reproductive-statuses', ReproductiveStatusController::class);

    //Family Classifications
    Route::get('/fam-classifications/list', [FamClassificationController::class, 'list'])->name('fam-classifications.list');
    Route::resource('fam-classifications', FamClassificationController::class);

    Route::get('/family-data/{id}', [FamilyController::class, 'getFamilyData'])->name('family.data');
    Route::get('/phone-data/{phone}', [FamilyController::class, 'getPhoneData'])->name('phone.data');
    Route::get('/pet-data/{id}', [FamilyController::class, 'getPetData'])->name('pet.data');


    //Pet Classifications
    Route::get('/pet-classifications/list', [PetClassificationController::class, 'list'])->name('pet-classifications.list');
    Route::resource('pet-classifications', PetClassificationController::class);

    // Shifts 
    Route::get('/shifts/list', [ShiftController::class, 'list'])->name('shifts.list');
    Route::resource('shifts', ShiftController::class);

    //Pets Statuses
    Route::get('/pets-statuses/list', [PetsStatusController::class, 'list'])->name('pets-statuses.list');
    Route::resource('pets-statuses', PetsStatusController::class);

    //Cover Areas
    Route::get('/cover-areas/list', [CoverAreaController::class, 'list'])->name('cover-areas.list');
    Route::resource('cover-areas', CoverAreaController::class);

    //Schedules
    Route::get('/schedules/list', [ScheduleController::class, 'list'])->name('schedules.list');
    Route::get('/schedules/get-events', [ScheduleController::class, 'getEvents'])->name('schedules.getEvents');
    Route::resource('schedules', ScheduleController::class);

    //Families
    Route::get('/families/list', [FamilyController::class, 'list'])->name('families.list');
    Route::get('/families/get-family-by-pet/{pet_id}', [ReceptionController::class, 'getFamilyByPet'])->name('families.getFamilyByPet');

    Route::resource('families', FamilyController::class);

    //Pets
    Route::get('/pets/list', [PetController::class, 'list'])->name('pets.list');
    Route::get('/pets/preview/{family}', [PetController::class, 'preview'])->name('pets.preview');

    Route::get('/pets/{pet}/family', [PetController::class, 'getFamilyByPet'])->name('pets.family');
    Route::resource('pets', PetController::class);

    //RECEPTIONS
    Route::put('/receptions/update/{id}',[ReceptionController::class, 'transfer'])->name('reception.transfer');
    Route::get('/receptions/list', [ReceptionController::class, 'list'])->name('reception.list');

    Route::get('/receptions/historial/{id}', [ReceptionController::class, 'historial'])->name('reception.historial');
    
    Route::get('/receptions/hospital/{id}', [ReceptionController::class, 'hospital_authorization'])->name('hospital.list');
    Route::post('/receptions/hospital/pdf/{id}', [ReceptionController::class, 'hospital_authorizationpdf'])->name('hospital.pdf');
    
    Route::resource('receptions', ReceptionController::class);



    //RECEPTIONS STATUS HISTORIES
    Route::get('/reception-status-histories', [ReceptionStatusHistoryController::class, 'list'])->name('reception-status.list');
    Route::resource('reception-status-histories', ReceptionStatusHistoryController::class);


    //ASSIGNAMENT  
    Route::get('/assignment/appointments', [AssignmentController::class, 'index'])->name('assignment.index');
    Route::get('/assignment/appointments/list', [AssignmentController::class, 'appointments'])->name('assignment.appointments');
    Route::get('/assignment/hospitaizations', [AssignmentController::class, 'hospital'])->name('assignment.hospital');
    Route::get('/assignment/hospitaizations/list', [AssignmentController::class, 'hospitalizations'])->name('assignment.hospitalizations');
    
    Route::get('/assignment/hospitalizations/altas', [AssignmentController::class, 'hospital_altas'])->name('hospitalization.altas');
    Route::get('/assignment/altas/list', [AssignmentController::class, 'altas'])->name('assignment.altas');

    //Appointments
    Route::get('/appointments/consultation/{id}', [AppointmentController::class, 'consultation'])->name('appointment.consultation');
    Route::get('/appointments/{id}', [AppointmentController::class, 'list'])->name('appointment.list');
    Route::get('/appointments/historic/{id}', [AppointmentController::class, 'historic'])->name('appointment.historic');
    Route::resource('appointments', AppointmentController::class);

    //PRESCRIPTIONS
    Route::get('/prescriptions/list', [PrescriptionController::class, 'list'])->name('prescription.list');
    Route::get("/prescriptions/create/{id}", [PrescriptionController::class, 'create'])->name('prescription.create');
    Route::get("/prescriptions/pdf/{id}", [PrescriptionController::class, "imprimir"])->name("prescription.imprimir");
    Route::resource('prescriptions', PrescriptionController::class);

    //PET-HISTORY
    Route::get('/pet-history/{id}', [PetHistoryController::class, 'index'])->name('pet-history.index');

    //SERVICES
    Route::get('/services/list', [ServiceController::class, 'list'])->name('services.list');
    Route::resource('services', ServiceController::class);

    //Vacucnation certificate
    Route::get('/vaccine-certificates/list', [VaccineCertificateController::class, 'list'])->name('certificate.list');
    Route::get("/vaccine-certificates/pdf/{id}", [VaccineCertificateController::class, "imprimir"])->name("certificate.imprimir");
    Route::resource('vaccine-certificates', VaccineCertificateController::class);

    //Hospitalizations
    Route::get('/alta-voluntaria/{id}', [HospitalizationController::class, 'altaVoluntaria'])->name('alta.voluntaria');
    Route::post('/alta-voluntaria/pdf/{id}', [HospitalizationController::class, 'altaVoluntariapdf'])->name('altaVoluntaria.pdf');
    Route::get('/hospitalizations/historic/{id}', [HospitalizationController::class, 'historic'])->name('hospitalization.historic');   
    Route::resource('hospitalizations', HospitalizationController::class);


    //PRODUCT CLASSIFICATIONS
    Route::resource('product-classifications', ProductClassificationController::class);

    //PRODUCT TYPES
    Route::resource('product-types', ProductTypeController::class);

    //RED SHEETS FOR HOSPITALIZATION DAYS
    Route::get('/hospitalizations/entries/{id}', [RedSheetController::class, 'entry'])->name("redsheet.entry");
    Route::get('/red-sheets/recap/{id}', [RedSheetController::class, 'recap'])->name("red-sheets.recap");
    Route::post('/redSheet/discharge', [redSheetController::class, 'discharge']);
    Route::post('/hospitalizations/discharge', [RedSheetController::class, 'dischargePatient']);
    Route::resource('red-sheets', RedSheetController::class);

    //SURGERIES
    Route::get('/surgeries/entries/{id}', [SurgeryController::class, 'entry'])->name("surgeries.entry");
    Route::get("/surgeries/create/{id}", [SurgeryController::class, 'create'])->name('surgeries.creater');
    Route::get('/check-surgeries-requirements/{id}', [SurgeryController::class, 'checkRequirements'])->name("surgery.checkRequirements");
    Route::get('/surgeries/authorization/{id}',[SurgeryController::class, 'surgery_authorization'])->name("surgery.auth");
    Route::post('/surgeries/authorization/pdf/{id}',[SurgeryController::class, 'surgery_authorizationpdf'])->name("surgery_authorization.pdf");
    Route::resource('surgeries', SurgeryController::class);
   
    //FOLLOW UPS 
    Route::get('/follow-ups/entries/{id}', [FollowUpController::class, 'entry'])->name("followup.entry");
    Route::resource('follow-ups', FollowUpController::class);

    //FORMAT TYPES
    Route::resource('format-types', FormatTypeController::class);

    //FORMATS
    Route::get("/formats/list", [FormatController::class, 'list'])->name('list.index');
    Route::get("/formats/list/{id}", [FormatController::class, 'listOne'])->name('formats.list');
    Route::get("/formats/created/{id}", [FormatController::class, 'format_list'])->name('formats.created');
    Route::get("/formats/create/{id}", [FormatController::class, 'add'])->name('formats.add');
    Route::get('/formats/hospital/{id}', [FormatController::class, 'hospital_authorization'])->name('format.hospital');
    Route::post('/formats/hospital/pdf/{id}', [FormatController::class, 'generateHospitalAuthorizationPdf'])->name('format-hospital.pdf'); 
    Route::get('/formats/alta/{id}', [FormatController::class, 'altaVoluntaria'])->name('format.alta');
    Route::post('/formats/alta/pdf/{id}', [FormatController::class, 'altaVoluntariapdf'])->name('format-alta.pdf'); 
    Route::get('/formats/surgery/{id}', [FormatController::class, 'surgery_authorization'])->name('format.surgery');
    Route::post('/formats/surgery/pdf/{id}', [FormatController::class, 'surgery_authorizationpdf'])->name('format-surgery.pdf'); 
    Route::resource('formats', FormatController::class);

    //Appointment Services
    Route::get('appointment-services/labs/{id}', [AppointmentServiceController::class, 'getLabs'])->name("appointment-services.labs");
    Route::get('appointment-services/imgs/{id}', [AppointmentServiceController::class, 'getImgs'])->name("appointment-services.imgs");
    Route::resource('appointment-services', AppointmentServiceController::class);


    //RoleHasPermissions
    Route::resource('role-has-permissions', RoleHasPermissionController::class);
    //HOSPITAL DISCHARGE
    Route::resource('hospital-discharges', HospitalDischargeController::class);

    //FOLLOWUPS INTERNS
    Route::resource('followup-interns', FollowupInternController::class);

    //FOLLOWUPS SURGICALS
    Route::resource('followup-surgicals', FollowupSurgicalController::class);
    
});
 