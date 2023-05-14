<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\User\Models\User\User;

class EmailsExists implements Rule
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
        $emails = explode('-', $value);
        foreach ($emails as $email) {
            $user = User::where('email',$email)->exists();
            if (!$user) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.rules.emailsexists');
    }
}
