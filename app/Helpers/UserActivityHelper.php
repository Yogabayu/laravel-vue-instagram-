<?php

namespace App\Helpers;

use App\Models\UserActivity;

class UserActivityHelper
{
    public static function logLoginActivity($userUuid, $message)
    {
        UserActivity::create([
            'user_uuid' => $userUuid,
            'desc' => $message,
        ]);
    }
}
