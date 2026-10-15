<?php

namespace App\Support\Auth;

use Symfony\Component\HttpFoundation\Cookie;

class AuthCookie
{
    private static function name(): string
    {
        return config('jwt.cookie_key_name', 'ictms_access_token');
    }

    public static function create(
        string $token,
        int $ttlSeconds
    ): Cookie {
        return cookie(
            name: self::name(),
            value: $token,
            minutes: (int) ceil($ttlSeconds / 60),
            path: '/',
            domain: null,
            secure: config('auth_cookie.secure', true),
            httpOnly: true,
            raw: false,
            sameSite: config('auth_cookie.same_site', 'lax')
        );
    }

    public static function forget(): Cookie
    {
        return cookie()->forget(
            self::name(),
            '/'
        );
    }
}
