<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends FormRequest
{
    public function rules()
    {
        $strongPassword = Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols();

        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'invitation' => ['required']
        ];
    }
}
