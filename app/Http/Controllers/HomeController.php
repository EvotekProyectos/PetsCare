<?php

namespace App\Http\Controllers;

use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\AttentionStatus;
use App\Models\CremationStatus;
use App\Models\FamClassification;
use App\Models\Family;
use App\Models\Genre;
use App\Models\GroomingStatus;
use App\Models\HospitalizationStatus;
use App\Models\HotelStatus;
use App\Models\Pet;
use App\Models\PetClassification;
use App\Models\Reason;
use App\Models\Reception;
use App\Models\ReproductiveStatus;
use App\Models\Room;
use App\Models\Species;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    /**
     * @method bool hasRole(string|array $roles)
     */
    public function index()
    {
        $user = auth()->user();

        foreach (config('role_routes') as $role => $routeName) {
            if ($user->hasRole($role)) {
                return redirect()->route($routeName);
            }
        }
        $reception = new Reception();
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::where("deceased", 0)->get();

        //catalogos necesarios para el modal rápido de nueva mascota/familia
        $family = new Family();
        $genders = Genre::all();
        $ReproductiveStatuses = ReproductiveStatus::all();
        $PetClassifications = PetClassification::all();
        $FamClassifications = FamClassification::all();
        $veterinarians = User::role('medico')->get();      // rol id 3
        $collaborators = User::role('colaborador')->get();
        $Species = Species::where('active', true)->orderBy('name')->get();

        //catalogos de estatus para los filtros de las 5 tablas de recepciones
        $attentionStatuses = AttentionStatus::all();
        $hospitalizationStatuses = HospitalizationStatus::all();
        $groomingStatuses = GroomingStatus::all();
        $hotelStatuses = HotelStatus::all();
        $cremationStatuses = CremationStatus::all();

        // Defaults de los filtros de estatus
        $defaultHospitalizationStatusId = HospitalizationStatus::where('name', 'Hospitalizado')->value('id');
        $defaultHotelStatusId = HotelStatus::where('name', 'En estancia')->value('id');
        $enEsperaAttentionStatusId = AttentionStatus::where('name', 'En espera')->value('id');


        $this->authorize("create", Reception::class);
        return view('reception.index', compact(
            'reception',
            'admissions',
            'areas',
            'families',
            'reasons',
            'users',
            'rooms',
            'pets',
            'family',
            'genders',
            'ReproductiveStatuses',
            'PetClassifications',
            'FamClassifications',
            'veterinarians',
            'collaborators',
            'Species',
            'attentionStatuses',
            'hospitalizationStatuses',
            'groomingStatuses',
            'hotelStatuses',
            'cremationStatuses',
            'defaultHospitalizationStatusId',
            'defaultHotelStatusId'
            ,
            'enEsperaAttentionStatusId'
        ));
    }

    public function megamenu()
    {
        $menus = [
            [
                'route' => route('logs.index'),
                'title' => 'Bitácora',
                'description' => 'Consulta eventos importantes.',
                'icon' => 'fas fa-history'

            ],
            [
                'route' =>  route('role-has-permissions.index'),
                'title' => 'Roles y permisos',
                'description' => 'Gestiona permisos para roles.',
                'icon' => 'fas fa-user'
            ],
            [
                'route' => route('users.index'),
                'title' => 'Usuarios',
                'description' => 'Administra información sobre usuarios',
                'icon' => 'fas fa-user'
            ],
            [
                'route' => route('rooms.index'),
                'title' => 'Consultorios',
                'description' => 'Administra información sobre consultorios.',
                'icon' => 'fas fa-first-aid'
            ],
            [
                'route' => route('shifts.index'),
                'title' => 'Turnos',
                'description' => 'Administra información sobre turnos.',
                'icon' => 'fa fa-clock'
            ],
            [
                'route' => route('cover-areas.index'),
                'title' => 'Áreas Horarios',
                'description' => 'Administra información sobre áreas a cubrir en horarios.',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('schedules.index'),
                'title' => 'Horarios',
                'description' => 'Administra información sobre horarios.',
                'icon' => 'fa fa-calendar-check'
            ],
            [
                'route' => route('reasons.index'),
                'title' => 'Motivos',
                'description' => 'Administra información sobre motivos.',
                'icon' => 'fas fa-list'
            ],
            [
                'route' =>  route('areas.index'),
                'title' => 'Áreas',
                'description' => 'Administra información sobre áreas.',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('attention-statuses.index'),
                'title' => 'Estados de atención',
                'description' => 'Administra información sobre estados de atención.',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('grooming-statuses.index'),
                'title' => 'Estados de grooming',
                'description' => 'Administra información sobre estados de grooming.',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('reception-types.index'),
                'title' => 'Tipos de recepción',
                'description' => 'Administra información sobre tipos de recepción.',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('genres.index'),
                'title' => 'Géneros',
                'description' => 'Administra información sobre géneros.',
                'icon' => 'fas fa-venus-mars'
            ],
            [
                'route' => route('admission-types.index'),
                'title' => 'Tipos de ingreso',
                'description' => 'Administra información sobre tipos de ingreso.',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('reproductive-statuses.index'),
                'title' => 'Estados reproductivos',
                'description' => 'Administra información sobre estados reproductivos.',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('fam-classifications.index'),
                'title' => 'Clasificación de Familias',
                'description' => 'Administra información sobre clasificación de familias',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('pet-classifications.index'),
                'title' => 'Clasificación de Mascotas',
                'description' => 'Administra información sobre clasificación de mascotas',
                'icon' => 'fas fa-list'
            ],
            [
                'route' => route('pets-statuses.index'),
                'title' => 'Estados de Mascotas',
                'description' => 'Administra información sobre estado de mascotas.',
                'icon' => 'fas fa-list'
            ],


        ];


        return view('megamenu.index', compact('menus'));
    }
}
