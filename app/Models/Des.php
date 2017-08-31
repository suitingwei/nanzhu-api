<?php

namespace App\Models;

use Crypt;
use JWTAuth;

class Des
{

    public static function userToken($user_id)
    {
        $token = Crypt::encrypt($user_id);
        return $token;
    }

    public static function tokenToUserId($token)
    {
        try {
            $userId = Crypt::decrypt($token);
        } catch (\Exception $e) {
            $userId = -1;
        }

        return $userId;
    }

}
