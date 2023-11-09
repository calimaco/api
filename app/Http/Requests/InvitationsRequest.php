<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvitationsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'invite' => ['required', 'email'],
            'api_access_period_days' => ['present', 'integer', 'nullable']
        ];
    }
}
