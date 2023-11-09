<?php

namespace App\Http\Controllers;

use App\Http\Services\RegistrationService;
use App\Http\Requests\RegistrationRequest;
use App\Traits\HttpResponses;
use App\Http\Services\AuthService;
use Illuminate\Support\Carbon;

class RegistrationController extends Controller
{
    use HttpResponses;

    private $service;

    public function __construct() {
        $this->service = new RegistrationService();
    }

    public function register(RegistrationRequest $request)
    {
        // Check invitation info
        $invitee = $this->service->findInvitee($request);

        if(!$invitee) {
            return $this->invalidCredentialsResponse();
        }

        // Check if invitation has expired
        $invitationValidity = $invitee->invitation_expiration_date;
        $currentTimestamp = Carbon::now();

        if($currentTimestamp > $invitationValidity) {
            return $this->invitationHasExpiredResponse();
        }

        // Create user account
        $newUser = $this->service->createUserAccount($request, $invitee->api_access_period_days);
  
        // Invalidate invitation token (as it was already used)
        $this->service->invalidateInvitationToken($invitee->id);
        
        // Generate first user's api token
        $auth = new AuthService();
        $newApiToken = $auth->generateNewApiToken($newUser);

        return $this->accountCreatedResponse($newApiToken, $newUser->deactivation_date);
    }

    private function invalidCredentialsResponse()
    {
        return $this->error(
            'Unauthorized',
            401,
            "Invalid credentials. Please check your email and invitation token and try again."
        );
    }

    private function invitationHasExpiredResponse()
    {
        return $this->error(
            'Unauthorized',
            401,
            "Sorry, your invitation token has expired or you've already used it to create an account. " .
            "Please reach out to our IT department."
        );
    }

    private function accountCreatedResponse($newApiToken, $acc_deactivation_date)
    {
        $deactivation_msg = isset($acc_deactivation_date)
                           ? "Your API access is active and will " .
                             "be available until $acc_deactivation_date, " . 
                             "after which it will be revoked."
                           : null;

        return $this->response(
            'Account created!' . " $deactivation_msg",
            200,
            ["api_token" => $newApiToken],
            "Here is your initial API token. To retrieve one in the future, visit /auth."
        );
    }
}
