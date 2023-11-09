<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\ApiToken;
use App\Utilities\CommonUtils;

class AuthService
{
    public function generateNewApiToken(User $user)
    {
        $this->nullifyEveryOtherApiTokenFromUser($user->id);

        $plainTextToken = CommonUtils::generateRandomToken(40);

        $newApiToken = new ApiToken();
        $newApiToken->user_id = $user->id;
        $newApiToken->api_token = hash('sha256', $plainTextToken);
        $newApiToken->status = 'active';
        $newApiToken->expiration_date = $user->deactivation_date;
        $newApiToken->last_used_at = null;
        $newApiToken->save();

        return $plainTextToken;
    }

    private function nullifyEveryOtherApiTokenFromUser($user_id)
    {
        return ApiToken::query()
            ->where('user_id', $user_id)
            ->where('status', 'active')
            ->update([
                'status' => 'inactive'
            ]);
    }
}