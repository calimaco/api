<?php

namespace App\Http\Services;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    public function findInvitee($request)
    {
        return Invitation::query()
            ->where('invited_email', $request->email)
            ->where('invitation_token', $request->invitation)
            ->first();
    }

    public function createUserAccount($request, $access_days)
    {
        $newUser = new User();

        $newUser->name = $request->name;
        $newUser->invitation_token = $request->invitation;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->role = 'user';
        $newUser->deactivation_date = isset($access_days)
                                    ? Carbon::now()->addDays($access_days)
                                    : null;
        $newUser->save();

        return $newUser;
    }

    public function invalidateInvitationToken($invitee_id)
    {
        return Invitation::query()
            ->where('id', $invitee_id)
            ->update([
                'token_was_used' => 1,
                'date_token_was_used' => Carbon::now()
            ]);
    }
}