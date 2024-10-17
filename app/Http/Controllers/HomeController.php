<?php

namespace App\Http\Controllers;

use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Family;
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
        $rooms= Room::all();

        $this->authorize("create", Reception::class);
        return view('reception.create', compact('reception','admissions','areas','families','reasons','users','rooms'));
    }
}
