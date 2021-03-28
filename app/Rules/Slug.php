<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
{
    protected $message;

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
    public function passes($attribute, $value): bool
    {
        if ($this->hasUnderscores($value)) {
            $this->message = __('validation.no_underscores');

            return false;
        }

        if ($this->itStartWithDash($value)) {
            $this->message = __('validation.no_beginning_dashes');

            return false;
        }
        if ($this->itEndsWithDash($value)) {
            $this->message = __('validation.no_ending_dashes');

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function hasUnderscores(mixed $value): bool
    {
        return preg_match("/_/", $value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function itStartWithDash(mixed $value): bool
    {
        return preg_match("/^-/", $value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function itEndsWithDash(mixed $value): bool
    {
        return preg_match("/-$/", $value);
    }
}
