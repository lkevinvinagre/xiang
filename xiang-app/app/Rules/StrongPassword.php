<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
           {
               if (strlen($value) < 8) {
                   $fail('The :attribute must be at least 8 characters.');
               }
               if (!preg_match('/[A-Z]/', $value)) {
                   $fail('The :attribute must contain at least one uppercase letter.');
               }
               if (!preg_match('/[a-z]/', $value)) {
                   $fail('The :attribute must contain at least one lowercase letter.');
               }
               if (!preg_match('/[0-9]/', $value)) {
                   $fail('The :attribute must contain at least one number.');
               }
               if (!preg_match('/[^a-zA-Z0-9\s]/', $value)) {
                   $fail('The :attribute must contain at least one special character.');
               }
           }
}
