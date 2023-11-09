<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use App\Http\Requests\AuthRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    use HttpResponses;

    private $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function auth(AuthRequest $request)
    {
        if(Auth::attempt($request->only('email', 'password'))) {
            $user_validity = Auth::user()->deactivation_date;
            if($user_validity > Carbon::now() || $user_validity === null) {
                $new_token = $this->service->generateNewApiToken(Auth::user());
                return $this->response(
                    'Authorized',
                    200,
                    [
                        "api_token" => $new_token,
                        'api_access_until' => $user_validity ?? 'infinity'
                    ],
                    "This API token is now your sole active token, rendering your other tokens inactive."
                );
            }
            else {
                return $this->response(
                    'Forbidden',
                    403,
                    [],
                    "Your account was deactivated on $user_validity, " .
                    "resulting in the loss of access to our API. " .
                    "Feel free to contact our IT department if you need " .
                    "to request renewed access"
                );
            }
        }
        else {
            return $this->response(
                'Unauthorized',
                401,
                [],
                "Invalid credentials. Check your email and password and try again."
            );
        }
    }
}
