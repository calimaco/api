<?php

namespace App\Http\Services;

use App\Models\Invitation;
use Illuminate\Support\Carbon;
use App\Utilities\CommonUtils;

class InvitationsService
{
    public function getInvitation($request)
    {
        $oldInvitation = $this->invitationAlreadyExists($request);

        if($oldInvitation) {
            return $oldInvitation;
        }

        $newInvitation = new Invitation();

        $newInvitation->invited_email = $request->invite;
        $newInvitation->invitation_token = CommonUtils::generateRandomToken(12);
        $newInvitation->inviter_email = $request->email;
        $newInvitation->role = 'user';
        $newInvitation->api_access_period_days = $request->api_access_period_days;
        $newInvitation->token_was_used = 0;
        $newInvitation->invitation_expiration_date = date('Y-m-d H:i:s', strtotime('+30 days'));

        $newInvitation->save();

        return [
            'email_to_invite' => $newInvitation->invited_email,
            'invitation_token' => $newInvitation->invitation_token,
            'invitation_expiration_date' => $newInvitation->invitation_expiration_date,
            'api_access_period_days' => $newInvitation->api_access_period_days ?? 'infinity'
        ];
    }

    public function invitationAlreadyExists($request)
    {
        $existingInv = Invitation::where('invited_email', $request->invite)
                        ->where('invitation_expiration_date', '>', Carbon::now())
                        ->where('api_access_period_days', $request->api_access_period_days)
                        ->where('invitation_expiration_date', '>', Carbon::now())
                        ->first();

        if($existingInv) {
            $existingInv->invitation_expiration_date = date('Y-m-d H:i:s', strtotime('+30 days'));
            $existingInv->save();

            return [
                'email_to_invite' => $existingInv->invited_email,
                'invitation_token' => $existingInv->invitation_token,
                'invitation_expiration_date' => $existingInv->invitation_expiration_date,
                'api_access_period_days' => $existingInv->api_access_period_days ?? 'infinity'
            ];
        }

        return null;
    }
}