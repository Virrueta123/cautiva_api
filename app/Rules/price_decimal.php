<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class price_decimal implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Valida que el valor tenga hasta dos decimales
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $value)) {
            $fail('El :attribute debe ser un número válido con hasta dos decimales.');
        }
    }

    
}
