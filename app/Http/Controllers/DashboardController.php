<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Reception;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('dashboard.view');

    }

  
//RECEPCIONES DEL DIA
public function appointmentsTotal()
{   
    $today = Carbon::today();

    $totalAppointments = Reception::where('reception_type_id', 1)->whereDate('created_at', $today)->count();
    $totalHospitalizations = Reception::where('reception_type_id', 2)->whereDate('created_at', $today)->count();
    $totalGrooming = Reception::where('reception_type_id', 3)->whereDate('created_at', $today)->count();
    $totalHotel = Reception::where('reception_type_id', 4)->whereDate('created_at', $today)->count();
    $totalCremations = Reception::where('reception_type_id', 5)->whereDate('created_at', $today)->count();

    return response()->json([
        'total_appointment' => $totalAppointments,
        'total_hospital' => $totalHospitalizations,
        'total_grooming' => $totalGrooming,
        'total_hotel' => $totalHotel,
        'total_cremation' => $totalCremations,
    ]);    
}


//CONSULTAS EN BASE A SU TIPO 
public function appointmentsReason(Request $request)
{
    $timeFilter = $request->input('time', 'week');
    $query = Reception::where('reception_type_id', 1);

    if ($timeFilter === 'custom') {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Fechas requeridas para filtro personalizado.'], 400);
        }

    $query->whereBetween(DB::raw('DATE(entry_date)'), [$startDate, $endDate]);
    } elseif ($timeFilter === 'week') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfWeek(), Carbon::now()]);
    } elseif ($timeFilter === 'month') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfMonth(), Carbon::now()]);
    } elseif ($timeFilter === 'year') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfYear(), Carbon::now()]);
    }

    $appointments = $query
        ->selectRaw('reason_id, COUNT(*) as total')
        ->groupBy('reason_id')
        ->get();

    return response()->json($appointments);
}

//CONSULTAS EN BASE A SU DIA, MES, AÑO O PERSONALIZADO
public function appointmentsDays(Request $request)
{
    try {
        $timeFilter = $request->input('time', 'week');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $start = null;
        $end = null;
        $groupBy = null;
        $range = [];
        $labels = [];

        if ($timeFilter === 'custom') {
            if (!$startDate || !$endDate) {
                return response()->json(['error' => 'Fechas inválidas'], 400);
            }

            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            if ($start->greaterThan($end)) {
                return response()->json(['error' => 'La fecha de inicio no puede ser mayor que la fecha de fin'], 400);
            }

            $groupBy = 'DATE(entry_date)';
            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $range[] = $date->format('Y-m-d');
                $labels[] = $date->format('d-m-Y');
            }
        } else {
            if ($timeFilter === 'week') {
                $start = Carbon::now()->startOfWeek();
                $groupBy = 'DAYOFWEEK(entry_date)';
                $range = range(1, 7);
                $labels = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado','Domingo'];
            } elseif ($timeFilter === 'month') {
                $start = Carbon::now()->startOfMonth();
                $groupBy = 'DAY(entry_date)';
                $daysInMonth = Carbon::now()->daysInMonth;
                $range = range(1, $daysInMonth);
                $labels = array_map(fn($day) => (string) $day, $range);
            } elseif ($timeFilter === 'year') {
                $start = Carbon::now()->startOfYear();
                $groupBy = 'MONTH(entry_date)';
                $range = range(1, 12);
                $labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            }
            $end = Carbon::now();
        }

        $currentData = Reception::where('reception_type_id', 1)
            ->whereBetween(DB::raw('DATE(entry_date)'), [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->selectRaw("$groupBy as period, COUNT(*) as total")
            ->groupBy('period')
            ->get()
            ->pluck('total', 'period')
            ->toArray();

        $formattedCurrent = array_fill_keys($range, 0);
        foreach ($currentData as $period => $total) {
            $formattedCurrent[$period] = $total;
        }

        return response()->json([
            'labels' => $labels,
            'current' => array_values($formattedCurrent),
            'dateRange' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d')
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error en appointmentsDays: ' . $e->getMessage());
        return response()->json(['error' => 'Error interno del servidor'], 500);
    }
}


//CONSULTAS EN BASE A SU VETERINARIO
public function appointmentsVet(Request $request)
{
    $timeFilter = $request->input('time', 'week');
    $query = Reception::where('reception_type_id', 1)->with(['vet', 'pet', 'family']);

    // Si el filtro es personalizado, asegúrate de que las fechas sean válidas
    if ($timeFilter === 'custom') {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Fechas requeridas para filtro personalizado.'], 400);
        }

        // Convertir las fechas a formato adecuado si es necesario (puedes usar Carbon si lo prefieres)
        $startDate = Carbon::parse($startDate)->startOfDay(); // Asegurarse que inicie al principio del día
        $endDate = Carbon::parse($endDate)->endOfDay(); // Asegurarse que termine al final del día

        $query->whereBetween(DB::raw('DATE(entry_date)'), [$startDate, $endDate]);
    } elseif ($timeFilter === 'week') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfWeek(), Carbon::now()]);
    } elseif ($timeFilter === 'month') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfMonth(), Carbon::now()]);
    } elseif ($timeFilter === 'year') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfYear(), Carbon::now()]);
    }

    // Obtener las citas agrupadas por veterinario
    $appointments = $query->get()->groupBy('veterinarian_id')->map(function ($appointments, $vetId) {
        $vetName = $appointments->first()->vet->name ?? 'Desconocido';

        return [
            'veterinarian' => $vetName,
            'total' => $appointments->count(),
            'appointments' => $appointments->map(function ($appointment) {
                return [
                    'date' => $appointment->entry_date ?? 'Desconocido',
                    'pet' => $appointment->pet->name ?? 'Desconocido',
                    'family' => $appointment->family->name ?? 'Desconocido',
                ];
            })
        ];
    })->values();

    return response()->json($appointments);
}


}

