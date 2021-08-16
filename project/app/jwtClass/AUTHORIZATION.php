<?php


namespace App\jwtClass;


class AUTHORIZATION
{

    public static function validateTimestamp($token)
    {
        $token = self::validateToken($token);
        if ($token != false /*&& (now() - $token->timestamp < (config('app.token_timeout') * 60))*/) {
            return $token;
        }
        return false;
    }
    public static function validateToken($token)
    {
        return JWT::decode($token, config('app.jwt_key'));
    }
    public static function generateToken($data)
    {
        return JWT::encode($data, config('app.jwt_key'));
    }
}
