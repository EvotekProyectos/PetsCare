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

    //  public function appointmentsTotal()
    //  {   
        
    //  $totalAppointments = Reception::where('reception_type_id', 1)->count();
    //  $totalHospitalizations = Reception::where('reception_type_id', 2)->count();
    //  $totalGrooming = Reception::where('reception_type_id', 3)->count();
    //  $totalHotel = Reception::where('reception_type_id', 4)->count();
    //  $totalCremations = Reception::where('reception_type_id', 5)->count();

    //  return response()->json([
    //      'total_appointment' => $totalAppointments,
    //      'total_hospital' => $totalHospitalizations,
    //      'total_grooming' => $totalGrooming,
    //      'total_hotel' => $totalHotel,
    //      'total_cremation' => $totalCremations,
    //  ]);    
    //  }

     public function appointmentsTotal(Request $request)
 {
     $timeFilter = $request->input('time', 'week'); // Valor por defecto: semana
     $query = Reception::query();

     // Filtrar por tiempo
     if ($timeFilter === 'week') {
         $query->whereBetween('entry_date', [Carbon::now()->startOfWeek(), Carbon::now()]);
     } elseif ($timeFilter === 'month') {
         $query->whereBetween('entry_date', [Carbon::now()->startOfMonth(), Carbon::now()]);
     } elseif ($timeFilter === 'year') {
         $query->whereBetween('entry_date', [Carbon::now()->startOfYear(), Carbon::now()]);
     }

     return response()->json([
         'total_appointment' => $query->where('reception_type_id', 1)->count(),
         'total_hospital' => $query->where('reception_type_id', 2)->count(),
         'total_grooming' => $query->where('reception_type_id', 3)->count(),
         'total_hotel' => $query->where('reception_type_id', 4)->count(),
         'total_cremation' => $query->where('reception_type_id', 5)->count(),
     ]);
 }


    


//     public function appointmentsReason()
// {   //Obtener las consultas en base a su reason
//     $appointments = Reception::where('reception_type_id', 1)
//         ->selectRaw('reason_id, COUNT(*) as total')
//         ->groupBy('reason_id')
//         ->get();
//     return response()->json($appointments);
// }

public function appointmentsReason(Request $request)
{
    $timeFilter = $request->input('time', 'week');
    $query = Reception::where('reception_type_id', 1);

    if ($timeFilter === 'week') {
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


//  public function appointmentsDays()
//  {    
//      $startOfWeek = Carbon::now()->startOfWeek(); // Lunes de la semana actual
//      $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek(); // Lunes de la semana pasada

//      // Consultas de la semana actual
//      $currentWeek = Reception::where('reception_type_id', 1)
//          ->whereBetween('entry_date', [$startOfWeek, Carbon::now()])
//          ->selectRaw('DAYOFWEEK(entry_date) as day, COUNT(*) as total')
//          ->groupBy('day')
//          ->get()
//          ->pluck('total', 'day')
//          ->toArray();

//      // Consultas de la semana pasada
//      $lastWeek = Reception::where('reception_type_id', 1)
//          ->whereBetween('entry_date', [$startOfLastWeek, $startOfLastWeek->copy()->endOfWeek()])
//          ->selectRaw('DAYOFWEEK(entry_date) as day, COUNT(*) as total')
//          ->groupBy('day')
//          ->get()
//          ->pluck('total', 'day')
//          ->toArray();

//      // Formatear resultados (Llenar con 0 los días sin datos)
//      $weekData = array_fill(1, 7, 0); // Domingo (1) a Sábado (7)
//      $lastWeekData = array_fill(1, 7, 0);

//      foreach ($currentWeek as $day => $total) {
//          $weekData[$day] += $total;
//      }

//      foreach ($lastWeek as $day => $total) {
//          $lastWeekData[$day] += $total;
//      }

//      return response()->json([
//          'current_week' => array_values($weekData),

//          'last_week' => array_values($lastWeekData)
//      ]);
 //}

 public function appointmentsDays(Request $request)
{    
    $timeFilter = $request->input('time', 'year');

    if ($timeFilter === 'week') {
        $start = Carbon::now()->startOfWeek();
        $lastStart = Carbon::now()->subWeek()->startOfWeek();
        $groupBy = 'DAYOFWEEK(entry_date)';
        $range = range(1, 7); // Días de la semana
    } elseif ($timeFilter === 'month') {
        $start = Carbon::now()->startOfMonth();
        $lastStart = Carbon::now()->subMonth()->startOfMonth();
        $groupBy = 'DAY(entry_date)';
        $range = range(1, Carbon::now()->daysInMonth); // Días del mes
    } elseif ($timeFilter === 'year') {
        $start = Carbon::now()->startOfYear();
        $lastStart = Carbon::now()->subYear()->startOfYear();
        $groupBy = 'MONTH(entry_date)';
        $range = range(1, 12); // Meses del año
    }

    $currentData = Reception::where('reception_type_id', 1)
        ->whereBetween('entry_date', [$start, Carbon::now()])
        ->selectRaw("$groupBy as period, COUNT(*) as total")
        ->groupBy('period')
        ->get()
        ->pluck('total', 'period')
        ->toArray();

    $lastData = Reception::where('reception_type_id', 1)
        ->whereBetween('entry_date', [$lastStart, $lastStart->copy()->endOfMonth()])
        ->selectRaw("$groupBy as period, COUNT(*) as total")
        ->groupBy('period')
        ->get()
        ->pluck('total', 'period')
        ->toArray();

    // Rellenar datos con ceros si no existen
    $formattedCurrent = array_fill_keys($range, 0);
    $formattedLast = array_fill_keys($range, 0);

    foreach ($currentData as $period => $total) {
        $formattedCurrent[$period] = $total;
    }

    foreach ($lastData as $period => $total) {
        $formattedLast[$period] = $total;
    }

    return response()->json([
        'labels' => $timeFilter === 'year' 
            ? ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
            : array_values($range),
            'labels' => $timeFilter === 'week' 
            ? ['Domingo','Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado']
            : array_values($range),
        'current' => array_values($formattedCurrent),
        'last' => array_values($formattedLast)
    ]);
}



// public function appointmentsVet()
// {   
//      //Obtener las consultas en base al medico
//     $appointments = Reception::where('reception_type_id', 1)->with('vet')
//         ->selectRaw('veterinarian_id, COUNT(*) as total')
//         ->groupBy('veterinarian_id')
//         ->get()
//         ->map(function ($appointment) {
//             return [
//                 'veterinarian' => $appointment->vet->name ?? 'Desconocido', 
//                 'total' => $appointment->total
//             ];
//         });
//     return response()->json($appointments);
// }

// public function appointmentsVet(Request $request)
// {
//     $timeFilter = $request->input('time', 'week');
//     //$query = Reception::where('reception_type_id', 1);
//     $query = Reception::where('reception_type_id', 1)->with(['vet', 'pet', 'family']);

//     if ($timeFilter === 'week') {
//         $query->whereBetween('entry_date', [Carbon::now()->startOfWeek(), Carbon::now()]);
//     } elseif ($timeFilter === 'month') {
//         $query->whereBetween('entry_date', [Carbon::now()->startOfMonth(), Carbon::now()]);
//     } elseif ($timeFilter === 'year') {
//         $query->whereBetween('entry_date', [Carbon::now()->startOfYear(), Carbon::now()]);
//     }

   
//     $appointments = $query->get()->groupBy('veterinarian_id')->map(function ($appointments, $vetId) {
//         $vetName = $appointments->first()->vet->name ?? 'Desconocido';

//         ->selectRaw('veterinarian_id, COUNT(*) as total')
//         ->groupBy('veterinarian_id')
//         ->get()
//         ->map(function ($appointment) {
//             return [
//                 'veterinarian' => $vetName,
//                 'total' => $appointments->count(),
//                 'appointments' => $appointments->map(function ($appointment) {
//                     return [
//                         'date' => $appointment->entry_date ?? 'Desconocido',
//                         'pet' => $appointment->pet->name ?? 'Desconocido',
//                         'family' => $appointment->family->name ?? 'Desconocido',
//                     ];
//                 })
//             ];
//         })->values(); // Para resetear los índices del array
    
//         return response()->json($appointments);
//     }

    public function appointmentsVet(Request $request)
{
    $timeFilter = $request->input('time', 'week');
    $query = Reception::where('reception_type_id', 1)->with(['vet', 'pet', 'family']);

    if ($timeFilter === 'week') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfWeek(), Carbon::now()]);
    } elseif ($timeFilter === 'month') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfMonth(), Carbon::now()]);
    } elseif ($timeFilter === 'year') {
        $query->whereBetween('entry_date', [Carbon::now()->startOfYear(), Carbon::now()]);
    }

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
    })->values(); // Para resetear los índices del array

    return response()->json($appointments);
}


}

