<?php

namespace App\Http\Controllers;

use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Reason;
use App\Models\Reception;
use App\Models\Room;
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
    public function index()
    {
        $reception = new Reception();
        $admissions = AdmissionType::all();
        $areas = Area::all();
        $families = Family::all();
        $reasons = Reason::all();
        $users = User::all();
        $rooms = Room::all();
        $pets = Pet::all();

        $this->authorize("create", Reception::class);
        return view('reception.create', compact('reception', 'admissions', 'areas', 'families', 'reasons', 'users', 'rooms', 'pets'));
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
