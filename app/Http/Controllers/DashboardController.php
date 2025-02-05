<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('dashboard.view');

    }

    public function appointmentsTotal()
    {   
        
    $totalAppointments = Reception::where('reception_type_id', 1)->count();
    $totalHospitalizations = Reception::where('reception_type_id', 2)->count();
    $totalGrooming = Reception::where('reception_type_id', 3)->count();
    $totalHotel = Reception::where('reception_type_id', 4)->count();
    $totalCremations = Reception::where('reception_type_id', 5)->count();

    return response()->json([
        'total_appointment' => $totalAppointments,
        'total_hospital' => $totalHospitalizations,
        'total_grooming' => $totalGrooming,
        'total_hotel' => $totalHotel,
        'total_cremation' => $totalCremations,
    ]);

        
    }
    


    public function appointmentsReason()
{   //Obtener las consultas en base a su reason
    $appointments = Reception::where('reception_type_id', 1)
        ->selectRaw('reason_id, COUNT(*) as total')
        ->groupBy('reason_id')
        ->get();
    return response()->json($appointments);
}


public function appointmentsDays()
{    
    $startOfWeek = Carbon::now()->startOfWeek(); // Lunes de la semana actual
    $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek(); // Lunes de la semana pasada

    // Consultas de la semana actual
    $currentWeek = Reception::where('reception_type_id', 1)
        ->whereBetween('entry_date', [$startOfWeek, Carbon::now()])
        ->selectRaw('DAYOFWEEK(entry_date) as day, COUNT(*) as total')
        ->groupBy('day')
        ->get()
        ->pluck('total', 'day')
        ->toArray();

    // Consultas de la semana pasada
    $lastWeek = Reception::where('reception_type_id', 1)
        ->whereBetween('entry_date', [$startOfLastWeek, $startOfLastWeek->copy()->endOfWeek()])
        ->selectRaw('DAYOFWEEK(entry_date) as day, COUNT(*) as total')
        ->groupBy('day')
        ->get()
        ->pluck('total', 'day')
        ->toArray();

    // Formatear resultados (Llenar con 0 los días sin datos)
    $weekData = array_fill(1, 7, 0); // Domingo (1) a Sábado (7)
    $lastWeekData = array_fill(1, 7, 0);

    foreach ($currentWeek as $day => $total) {
        $weekData[$day] += $total;
    }

    foreach ($lastWeek as $day => $total) {
        $lastWeekData[$day] += $total;
    }

    return response()->json([
        'current_week' => array_values($weekData),
        'last_week' => array_values($lastWeekData)
    ]);
}

public function appointmentsVet()
{   
     //Obtener las consultas en base al medico
    $appointments = Reception::where('reception_type_id', 1)->with('vet')
        ->selectRaw('veterinarian_id, COUNT(*) as total')
        ->groupBy('veterinarian_id')
        ->get()
        ->map(function ($appointment) {
            return [
                'veterinarian' => $appointment->vet->name ?? 'Desconocido', 
                'total' => $appointment->total
            ];
        });
    return response()->json($appointments);
}


}

