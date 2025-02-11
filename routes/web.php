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
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetDetailController;
use App\Http\Controllers\CmTypeController;
use App\Http\Controllers\CremationController;
use App\Http\Controllers\CubicleController;
use App\Http\Controllers\CubicleTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowupsCriticController;
use App\Http\Controllers\FollowupInternController;
use App\Http\Controllers\FollowupSurgicalController;
use App\Http\Controllers\GroomingController;
use App\Http\Controllers\GroomingStatusController;
use App\Http\Controllers\GroomingStatusHistoryController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReproductiveStatusController;
use App\Http\Controllers\VaccineCertificateController;
use App\Http\Controllers\ProductClassificationController;
use App\Http\Controllers\ReceptionStatusHistoryController;
use App\Http\Controllers\RedSheetController;
use App\Http\Controllers\StatusSurgeryController;
use App\Http\Controllers\SurgeryScheduleController;
use App\Http\Controllers\TagTypeController;
use App\Models\Dashboard;
use App\Models\FollowUp;
use App\Models\Hospitalization;
use App\Models\Hotel;
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

    //GROOMING STATUSES
    Route::get('/grooming-statuses/list', [GroomingStatusController::class, 'list'])->name('grooming-statuses.list');
    Route::resource('grooming-statuses', GroomingStatusController::class);

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
    Route::get('/pets/data/{family}', [PetController::class, 'data'])->name('pets.data');
    // Route::get('/pets/{pet}/family', [PetController::class, 'getFamilyByPet'])->name('pets.family');
    Route::resource('pets', PetController::class);

    //RECEPTIONS
    Route::put('/receptions/update/{id}',[ReceptionController::class, 'transfer'])->name('reception.transfer');
    Route::get('/receptions/list', [ReceptionController::class, 'list'])->name('reception.list');

    Route::get('/receptions/list/appointments', [ReceptionController::class, 'listAppointments'])->name('reception.appointments');
    Route::get('/receptions/list/hospitalizations', [ReceptionController::class, 'listHospitalizations'])->name('reception.hospitalizations');
    Route::get('/receptions/list/groomings', [ReceptionController::class, 'listGroomings'])->name('reception.groomings');
    Route::get('/receptions/list/hotels', [ReceptionController::class, 'listHotels'])->name('reception.hotels');
    Route::get('/receptions/list/cremations', [ReceptionController::class, 'listCremations'])->name('reception.cremations');

    Route::get('/receptions/historial/{id}', [ReceptionController::class, 'historial'])->name('reception.historial');
    
    Route::get('/receptions/hospital/{id}', [ReceptionController::class, 'hospital_authorization'])->name('hospital.list');
    Route::post('/receptions/hospital/pdf/{id}', [ReceptionController::class, 'hospital_authorizationpdf'])->name('hospital.pdf');
    Route::get('/receptions/grooming/{id}', [ReceptionController::class, 'groomingservice'])->name('receptions.grooming');
    
    Route::resource('receptions', ReceptionController::class);



    //RECEPTIONS STATUS HISTORIES
    Route::get('/reception-status-histories/list', [ReceptionStatusHistoryController::class, 'list'])->name('reception-status.list');
    Route::resource('reception-status-histories', ReceptionStatusHistoryController::class);

    //GROOMING STATUS HISTORIES
    Route::get('/grooming-status-histories/list', [GroomingStatusHistoryController::class, 'list'])->name('grooming-status-histories.list');
    Route::resource('grooming-status-histories', GroomingStatusHistoryController::class);

    //ASSIGNAMENT  
    Route::get('/assignment/appointments', [AssignmentController::class, 'index'])->name('assignment.index');
    Route::get('/assignment/appointments/list', [AssignmentController::class, 'appointments'])->name('assignment.appointments');
    Route::get('/assignment/hospitaizations', [AssignmentController::class, 'hospital'])->name('assignment.hospital');
    Route::get('/assignment/hospitaizations/list', [AssignmentController::class, 'hospitalizations'])->name('assignment.hospitalizations');
    
    Route::get('/assignment/hospitalizations/altas', [AssignmentController::class, 'hospital_altas'])->name('hospitalization.altas');
    Route::get('/assignment/altas/list', [AssignmentController::class, 'altas'])->name('assignment.altas');
    Route::get('/assignment/groomings', [AssignmentController::class, 'groomings'])->name('assignment.groomings');

    //Appointments
    Route::get('/appointments/consultation/{id}', [AppointmentController::class, 'consultation'])->name('appointment.consultation');
    Route::get('/appointments/pv/{id}/{concepto}', [AppointmentController::class, 'ordenventa'])->name('appointment.pay');
    Route::get('/appointments/{id}', [AppointmentController::class, 'list'])->name('appointment.list');
    Route::get('/appointments/historic/{id}', [AppointmentController::class, 'historic'])->name('appointment.historic');
    Route::resource('appointments', AppointmentController::class);

    //PRESCRIPTIONS
    Route::get('/prescriptions/list', [PrescriptionController::class, 'list'])->name('prescription.list');
    Route::get('/prescriptions/discharge/{id}',  [PrescriptionController::class, 'new'])->name('prescriptions.new');
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
    Route::get('/hospitalizations/historic/{id}', [HospitalizationController::class, 'historic'])->name('hospitalization.historic');
    Route::get('/hospitalizations/follow-ups/{id}', [HospitalizationController::class, 'followups'])->name('hospitalization.followups');
    Route::post('/hospitalization/discharge', [HospitalizationController::class, 'discharge'])->name('hospitalization.discharge');
    Route::post('/discharge-death', [HospitalizationController::class, 'dischargeDeath'])->name('discharge-death');
    Route::get('/alta-voluntaria/{id}', [HospitalizationController::class, 'altaVoluntaria'])->name('alta.voluntaria');
    Route::post('/alta-voluntaria/pdf/{id}', [HospitalizationController::class, 'altaVoluntariapdf'])->name('altaVoluntaria.pdf');
    Route::get('/hospitalizations/historic/{id}', [HospitalizationController::class, 'historic'])->name('hospitalization.historic');   
    Route::resource('hospitalizations', HospitalizationController::class);


    //PRODUCT CLASSIFICATIONS
    Route::resource('product-classifications', ProductClassificationController::class);

    //PRODUCT TYPES
    Route::resource('product-types', ProductTypeController::class);

    //RED SHEETS FOR HOSPITALIZATION DAYS
    Route::get('/red-sheets/pv/{id}', [RedSheetController::class, 'ordenventa'])->name("redsheet.pay");
    Route::get('/hospitalizations/entries/{id}', [RedSheetController::class, 'entry'])->name("redsheet.entry");
    Route::get('/red-sheets/recap/{id}', [RedSheetController::class, 'recap'])->name("red-sheets.recap");
    Route::post('/redSheet/discharge', [RedSheetController::class, 'discharge'])->name("redsheet-discharge");
    Route::post('/hospitalizations/discharge', [RedSheetController::class, 'dischargePatient']);
    Route::post('/red-sheets/death', [RedSheetController::class, 'ButtonDeath'])->name("button-death");
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
    Route::get('/reporte', [FormatController::class, 'reporte']);
    Route::get('/pension/{id}', [FormatController::class, 'pensionFormat'])->name('pension.format');
    Route::get('/pension/pdf/{id}', [FormatController::class, 'pensionPdf'])->name('pension.pdf');
    // Route::get('/pension/inf/{id}', [FormatController::class, 'pensionDatos'])->name('pension.inf');
    Route::get('/formats/responsiva//EG/{id}', [FormatController::class, 'responsivaEg'])->name('format.responsivaEG');
    Route::post('/formats/responsiva/EG/pdf/{id}', [FormatController::class, 'responsivaPdf'])->name('format-responsiva.pdf');
    Route::resource('formats', FormatController::class);

    //Appointment Services
    Route::get('appointment-services/labs/{id}', [AppointmentServiceController::class, 'getLabs'])->name("appointment-services.labs");
    Route::get('appointment-services/imgs/{id}', [AppointmentServiceController::class, 'getImgs'])->name("appointment-services.imgs");
    Route::resource('appointment-services', AppointmentServiceController::class);

    

    //Budgets
    Route::get('/budgets/list', [BudgetController::class, 'list'])->name('budgets.list');
    Route::get('budgets/test/pdf/{id}', [BudgetController::class, 'generatePdf'])->name('budgets.test.pdf');
    Route::get('/budgets/sign/pdf/{id}', [BudgetController::class, 'budgetsign'])->name('budget.sign');
    Route::post('/budgets/authorization/pdf/{id}', [BudgetController::class, 'budgetpdf'])->name('budget.pdf');
    Route::post('/budgets/new/total/{id}', [BudgetController::class, 'newtotal'])->name('budget.new.total');
    Route::resource('budgets', BudgetController::class);

    //Budget Details
    Route::get('/budget-details/list/{id}', [BudgetDetailController::class, 'list'])->name('budget-details.list');
    Route::get('/budget-details/price/{id}', [BudgetDetailController::class, 'price'])->name('budget-details.price');
    Route::resource('budget-details', BudgetDetailController::class);

    //Follow Ups Type Critics
    Route::get('/followups-critics/list/{id}', [FollowupsCriticController::class, 'list'])->name('followups-critics.list');
    Route::resource('followups-critics', FollowupsCriticController::class);
    
    //HOSPITAL DISCHARGE
    Route::resource('hospital-discharges', HospitalDischargeController::class);

    //FOLLOWUPS INTERNS
    Route::get('/followup-interns/list/{id}', [FollowupInternController::class, 'list'])->name('followup-interns.list');
    Route::resource('followup-interns', FollowupInternController::class);

    //FOLLOWUPS SURGICALS
    Route::get('/followup-surgicals/list/{id}', [FollowupSurgicalController::class, 'list'])->name('followup-surgicals.list');
    Route::resource('followup-surgicals', FollowupSurgicalController::class);

    //Groomings
    Route::get('/groomings/history/{id}', [GroomingController::class, 'history'])->name('grooming.history');
    Route::get('/groomings/sign/pdf/{id}', [GroomingController::class, 'groomingsign'])->name('grooming.sign');
    Route::post('/groomings/authorization/pdf/{id}', [GroomingController::class, 'groomingpdf'])->name('grooming.pdf');
    Route::get('test/pdf/{id}', [GroomingController::class, 'generatePdf'])->name('test.pdf');
    Route::get('/groomings/pv/{id}', [GroomingController::class, 'ordenventa'])->name("grooming.pay");
    Route::get('/groomings/list/{id}', [GroomingController::class, 'list'])->name('groomings.list');
    Route::post('/grooming/update/status', [GroomingController::class, 'status'])->name('grooming.status');
    Route::resource('groomings', GroomingController::class);

    //RoleHasPermissions
    Route::resource('role-has-permissions', RoleHasPermissionController::class);

    //TAG TYPES
    Route::get('/tags/list', [TagTypeController::class, 'list'])->name('tag.list');
    Route::resource('tag-types', TagTypeController::class);

    //CM TYPES
    Route::get('/cm/list', [CmTypeController::class, 'list'])->name('cm.list');
    Route::resource('cm-types', CmTypeController::class);

    //CREMATIONS
    Route::get('/cremations/history/{id}', [CremationController::class, 'history'])->name('cremation.history');
    Route::get('/cremations/pv/{id}', [CremationController::class, 'ordenventa'])->name("cremation.pay");
    Route::get('/cremations/list', [CremationController::class, 'list'])->name('cremation.list');
    Route::get('/cremations/new/{id}', [CremationController::class, 'new'])->name('new.cremation');
    Route::get("/cremations/pdf/{id}", [CremationController::class, "comprobante"])->name("cremation.comprobante");
    Route::post('/cremations/{id}/updateStatus', [CremationController::class, 'updateStatus'])->name("cremation.updateStatus");
    Route::resource('cremations', CremationController::class);
    
    //Status surgery
    Route::resource('status-surgeries', StatusSurgeryController::class);

    //SURGERY SCHEDULES
    Route::get('/surgery-schedules/list', [SurgeryScheduleController::class, 'list'])->name('schedules-surgery.list');
    Route::get('/surgery-schedules/get-events', [ SurgeryScheduleController::class, 'getEvents'])->name('surgery-schedules.getEvents');
    Route::get('assignment/surgeries', [ SurgeryScheduleController::class, 'assignament'])->name('assignament.surgery');
    Route::post('/surgery/{id}/update/', [SurgeryScheduleController::class, 'updateStatus'])->name("surgery.status");
    Route::get('surgery-schedules/add/{id}', [SurgeryScheduleController::class, 'add'])->name("surgery-schedule.new");
    Route::resource('surgery-schedules', SurgeryScheduleController::class);

    //TYPES CUBICLES
    Route::get('/cubicle-types/list', [CubicleTypeController::class, 'list'])->name('cubicle-types.list');
    Route::resource('cubicle-types', CubicleTypeController::class);

    //CUBICLES
    Route::get('/cubicles/view', [CubicleController::class, 'view'])->name('cubicle.view');
    Route::get('/cubicles/list', [CubicleController::class, 'list'])->name('cubicles.list');
    Route::resource('cubicles', CubicleController::class);

    //HOTEL
    Route::get("/hotel/history/{id}", [HotelController::class, 'history'])->name('hotel.history');
    Route::get("/hotel/create/{id}", [HotelController::class, 'create'])->name('hotel.create');
    Route::get("/hotel/format/{id}", [HotelController::class, 'hotelFormat'])->name('hotel.format');
    Route::post("/hotel/pdf/{id}", [HotelController::class, 'FormatPdf'])->name('hotel.pdf');
    Route::get("/hotel/list/{id}", [HotelController::class, 'list'])->name('hotel.list');
    Route::get("/hotel/all", [HotelController::class, 'all'])->name('hotel.all');
    Route::get("/hotel/cubicles/view", [HotelController::class, 'view'])->name('hotel.view');
    Route::get('/hotel/pv/{id}', [HotelController::class, 'ordenventa'])->name("hotel.pay");
    Route::get('/pension/inf/{id}', [HotelController::class, 'pensionInf'])->name('pension.inf');
    Route::resource('hotels', HotelController::class);

    //Notificacions
    Route::get('/notifications',[NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/list',[NotificationController::class, 'list'])->name('notifications.list');
    Route::get('/notifications/list/unread',[NotificationController::class, 'unreadList'])->name('notifications.unreadList');
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');


    //DASHBOARD
    Route::get('/dash', [DashboardController::class, 'dashboard'])->name('dash.inf');
    Route::get('/dash/appointments-total', [DashboardController::class, 'appointmentsTotal'])->name('dash.appointments');
    Route::get('/dash/appointments-reasons', [DashboardController::class, 'appointmentsReason'])->name('dash.appointmentsReasons');
    Route::get('/dash/appointments-days', [DashboardController::class, 'appointmentsDays'])->name('dash.appointmentsDays');
    Route::get('/dash/appointments-vets', [DashboardController::class, 'appointmentsVet'])->name('dash.appointmentsVets');

});

 
