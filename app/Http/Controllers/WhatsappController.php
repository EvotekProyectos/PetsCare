<?php

namespace App\Http\Controllers;

use App\Models\ControlDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{
    public function enviar()
    {
        $start = Carbon::tomorrow()->startOfDay();
        $end = Carbon::tomorrow()->endOfDay();
        

        $citas = ControlDate::with(['family', 'dateType', 'pet'])
            ->whereBetween('date', [$start, $end])
            ->where('status_date_id', 1) // ajusta según tu lógica
            ->get();

        //  $query = ControlDate::with(['family', 'dateType', 'pet'])
        //      ->whereBetween('date', [$start, $end])
        //      ->where('status_date_id', 1);

        //  dd($query->toSql(), $query->getBindings());

        // Preparar datos para el JSON que consumirá Puppeteer
        $datosParaEnviar = $citas->map(function ($cita) {
            return [
                'number' => $cita->family->phone ?? null, // Número de teléfono de la familia
                'name' => $cita->pet ? $cita->pet->name : 'Mascota', // Nombre de la mascota si existe
                'date_type' => $cita->dateType ? $cita->dateType->name : 'Tipo de cita',
               'fecha' => \Carbon\Carbon::parse($cita->date)->format('d/m/Y'),

            ];
        })->filter(function ($item) {
            // Filtrar solo aquellos que tengan número telefónico
            return !empty($item['number']);
        });

        if ($datosParaEnviar->isEmpty()) {
            return redirect()->back()->with('success', 'No hay citas para enviar mensajes mañana.');
        }

        // Guardar JSON
        $jsonPath = base_path('whatsapp-sender/citas.json');
        file_put_contents($jsonPath, $datosParaEnviar->toJson());

        // Ejecutar el script Node.js desde Laravel
        $comando = 'cd ' . base_path('whatsapp-sender') . ' && node sendMessages.js';
        exec($comando . ' > /dev/null 2>&1 &');

        return redirect()->back()->with('success', 'Se están enviando los mensajes por WhatsApp.');
    }
}
