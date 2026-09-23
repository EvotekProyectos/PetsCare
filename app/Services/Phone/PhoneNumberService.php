<?php

namespace App\Services\Phone;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Única clase que conoce libphonenumber. Cualquier lógica de parseo,
 * validación o normalización de teléfonos debe pasar por aquí.
 */
class PhoneNumberService
{
    public const DEFAULT_REGION = 'MX';

    private PhoneNumberUtil $phoneUtil;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Normaliza un teléfono al formato canónico E.164 (ej. +528441234567).
     *
     * @param string|null $raw Número tal como lo escribió el usuario, idealmente ya con "+" y código de país.
     * @param string|null $defaultRegion Región (ISO 3166-1 alpha-2) a asumir si $raw no trae código de país.
     *
     * @throws NumberParseException Si el número no puede interpretarse o no es válido.
     */
    public function toE164(?string $raw, ?string $defaultRegion = self::DEFAULT_REGION): string
    {
        if (blank($raw)) {
            throw new NumberParseException(NumberParseException::NOT_A_NUMBER, 'El número de teléfono está vacío.');
        }

        $number = $this->phoneUtil->parse($raw, $defaultRegion);

        if (! $this->phoneUtil->isValidNumber($number)) {
            throw new NumberParseException(NumberParseException::NOT_A_NUMBER, 'El número de teléfono no es válido.');
        }

        return $this->phoneUtil->format($number, PhoneNumberFormat::E164);
    }

    /**
     * Indica si $raw puede normalizarse a un número real y válido.
     */
    public function isValid(?string $raw, ?string $defaultRegion = self::DEFAULT_REGION): bool
    {
        try {
            $this->toE164($raw, $defaultRegion);

            return true;
        } catch (NumberParseException) {
            return false;
        }
    }

    /**
     * Devuelve la región (ISO 3166-1 alpha-2, ej. "MX", "US", "CA") de un número ya en E.164.
     * Distingue correctamente países que comparten código, como +1 (US/CA/Caribe).
     */
    public function regionFor(string $e164): ?string
    {
        try {
            $number = $this->phoneUtil->parse($e164, self::DEFAULT_REGION);

            return $this->phoneUtil->getRegionCodeForNumber($number);
        } catch (NumberParseException) {
            return null;
        }
    }
}
