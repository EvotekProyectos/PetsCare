<?php

namespace App\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use RuntimeException;

/**
 * Señala, dentro de la transacción de PetController::quickCreate(), en qué
 * mitad del payload (family o pet) falló la validación. Necesario porque
 * ambos conjuntos de campos comparten nombres (ej. "name") y el frontend usa
 * un mapa de errores distinto para cada uno (ver QC_FAMILY_FIELD_MAP /
 * QC_PET_FIELD_MAP en petQuickCreate.js) — sin este dato no hay forma de
 * saber a cuál de los dos aplicar el error devuelto.
 */
class PetQuickCreateValidationException extends RuntimeException
{
    public function __construct(public readonly string $scope, public readonly Validator $validator)
    {
        parent::__construct("Validación fallida ({$scope}) en la creación rápida de mascota.");
    }
}
