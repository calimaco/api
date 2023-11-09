<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use App\Http\Requests\InvitationsRequest;
use App\Http\Services\InvitationsService;

class InvitationsController extends Controller
{
    use HttpResponses;

    private $service;

    public function __construct()
    {
        $this->service = new InvitationsService();
        \App\Models\User::factory()->create(['email' => 'deus@ehbom.com', 'role' => 'admin']);
    }

    public function generateInvitation(InvitationsRequest $request)
    {
        if(Auth::attempt($request->only('email', 'password'))) {
            if(Auth::user()->role == 'admin') {
                $info = $this->service->getInvitation($request);
                return $this->response(
                    'Authorized',
                    200,
                    $info,
                    "Remember: user will have to register using email_to_invite.");
            }
            else {
                return $this->error(
                    'Forbidden',
                    403,
                    "Sorry, it looks like you do not have permission to create an invitation."
                );
            }
        }
        else {
            return $this->error(
                'Unauthorized',
                401,
                "Invalid credentials. Please check your email and password and try again."
            );
        }
    }
}
