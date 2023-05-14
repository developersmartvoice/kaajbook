<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // return preg_match('/^\d{4}-\d{3}-\d{4}$/', $value) === 1; #4444-333-4444
        return preg_match('/^(?:[+])?(\d[ -]?){1,15}$/', $value) === 1; #(+)(1-15[ -d])
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.rules.phone');
    }
}
