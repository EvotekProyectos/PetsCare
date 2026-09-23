<?php

namespace App\Services;

use App\Models\Format;
use App\Models\HospitalizationStatus;
use App\Models\HospitalizationStatusHistory;
use App\Models\Reception;
use App\Models\Surgery;
use Illuminate\Support\Collection;

/**
 * Fuente única de verdad para "qué responsivas requiere una Reception,
 * cuáles de ellas ya están firmadas, y cuál sigue pendiente" — reutilizada
 * por ReceptionController (modal de Documentos, autorización de Hospital)
 * y SurgeryController (autorización de Procedimientos Anestésicos y
 * Quirúrgicos), para no mantener el catálogo de reglas duplicado entre
 * ambos ni entre las distintas formas de llegar a firmar cada responsiva
 * (creación directa, traslado, o el modal de Documentos).
 */
class ReceptionDocumentService
{
    private const AREA_QUIRURGICOS_ID = 1;

    /**
     * Catálogo de responsivas requeridas según tipo de recepción y área,
     * con la ruta de la vista de captura/firma correspondiente. Consulta
     * (1) no requiere ninguna. Hospitalización (2) además requiere la
     * quirúrgica (3) si el área asignada es Quirúrgicos o si ya existe una
     * Surgery asociada (cualquiera de las dos basta, no es fija como las
     * demás — un área cambiada después de registrar la cirugía sigue
     * exigiéndola). fallback_name es solo por si el catálogo FormatType no
     * trae el registro esperado (no debería pasar, ver FormatTypeSeeder).
     */
    public function requiredFormatsFor(Reception $reception): array
    {
        $required = match ((int) $reception->reception_type_id) {
            2 => [1 => ['route' => 'hospital.list', 'fallback_name' => 'Autorización para Hospitalización']],
            3 => [4 => ['route' => 'grooming.sign', 'fallback_name' => 'Responsiva Grooming']],
            4 => [5 => ['route' => 'hotel.format', 'fallback_name' => 'Responsiva Pensión']],
            5 => [7 => ['route' => 'cremation.responsiva', 'fallback_name' => 'Responsiva cremación']],
            default => [],
        };

        if (
            (int) $reception->reception_type_id === 2
            && ($this->isQuirurgicosArea($reception) || Surgery::where('reception_id', $reception->id)->exists())
        ) {
            $required[3] = ['route' => 'surgery.auth', 'fallback_name' => 'Autorización de Procedimientos Anestésicos y Quirúrgicos'];
        }

        return $required;
    }

    /**
     * area_id === 1: mismo criterio numérico que ya usaba
     * hospital_auth.js para decidir si encadena a la quirúrgica al firmar.
     */
    public function isQuirurgicosArea(Reception $reception): bool
    {
        return (int) $reception->area_id === self::AREA_QUIRURGICOS_ID;
    }

    /**
     * Responsivas requeridas (ver requiredFormatsFor()) que a $reception
     * todavía le falten -sin Format asociado-, en el mismo orden/keys que
     * el catálogo (format_type_id => ['route' => ..., 'fallback_name' => ...]).
     */
    public function missingFormatsFor(Reception $reception): Collection
    {
        $existingTypeIds = Format::where('reception_id', $reception->id)->pluck('format_type_id');

        return collect($this->requiredFormatsFor($reception))
            ->reject(fn ($meta, $formatTypeId) => $existingTypeIds->contains($formatTypeId));
    }

    /**
     * La primera responsiva requerida que todavía falte, o null si ya
     * están todas firmadas. Se usa para encadenar el siguiente formulario
     * justo después de firmar uno (ver hospital_authorizationpdf()/
     * SurgeryController::surgery_authorizationpdf()) — el orden lo decide
     * requiredFormatsFor(), así que da igual cuál se firme primero: siempre
     * termina apuntando a la que falte, o a null cuando ya no falta ninguna.
     */
    public function nextMissingFormat(Reception $reception): ?array
    {
        $missing = $this->missingFormatsFor($reception);

        return $missing->isEmpty() ? null : $missing->first();
    }

    /**
     * Si $reception (Hospitalización) ya tiene TODAS sus responsivas
     * requeridas completas (ver missingFormatsFor()) y todavía sigue
     * "Trasladado", la marca "Hospitalizado" — es la firma de la ÚLTIMA
     * responsiva pendiente la que finalmente admite al paciente, sin
     * importar cuál de las dos (Hospital o Quirúrgica, en área
     * Quirúrgicos) se firmó al final; en cualquier otra área, la única
     * requerida es la de Hospital. No hace nada si ya está "Hospitalizado"
     * (una hospitalización creada directamente ya nace así — ver
     * ReceptionController::store() — esto evita sembrar una entrada
     * duplicada) ni si todavía falta alguna responsiva. Llamado desde
     * ReceptionController::hospital_authorizationpdf() y
     * SurgeryController::surgery_authorizationpdf(), justo después de
     * guardar cada Format.
     */
    public function advanceToHospitalizadoIfComplete(Reception $reception): void
    {
        if ((int) $reception->reception_type_id !== 2) {
            return;
        }

        $hospitalizadoId = HospitalizationStatus::where('name', 'Hospitalizado')->value('id');
        $alreadyHospitalizado = (int) $reception->currentHospitalizationStatus?->hospitalization_status_id === (int) $hospitalizadoId;

        if ($alreadyHospitalizado) {
            return;
        }

        if ($this->missingFormatsFor($reception)->isNotEmpty()) {
            return;
        }

        HospitalizationStatusHistory::create([
            'reception_id' => $reception->id,
            'hospitalization_status_id' => $hospitalizadoId,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);
    }
}
