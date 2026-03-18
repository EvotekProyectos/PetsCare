<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\ControlDate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ControlDatesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        return ControlDate::with(['pet', 'dateType'])
            ->whereDate('date', '=', $tomorrow)
            ->get()
            ->map(function ($controlDate) {
                return [
                    // 'ID' => $controlDate->id,
                    'Numero'=> $controlDate->family->phone,
                    'Mascota' => $controlDate->pet->name ?? 'Sin nombre',
                    'Tipo de Cita' => $controlDate->dateType->name ?? 'Sin tipo',
                    'Fecha y Hora' => \Carbon\Carbon::parse($controlDate->date)->format('Y-m-d H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return ['Numero telefonico','Mascota', 'Tipo de Cita', 'Fecha y Hora'];
    }
}
