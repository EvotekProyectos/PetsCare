<?php

namespace App\Rules;

use App\Services\Phone\PhoneNumberService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valida que un valor sea un número de teléfono real (no solo un string cualquiera),
 * usando PhoneNumberService (libphonenumber) como única autoridad de validación.
 */
class ValidPhoneNumber implements ValidationRule
{
    public function __construct(private readonly ?string $defaultRegion = PhoneNumberService::DEFAULT_REGION)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! app(PhoneNumberService::class)->isValid($value, $this->defaultRegion)) {
            $fail('El :attribute no es un número de teléfono válido.');
        }
    }
}
