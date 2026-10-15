<?php

return [
    'secure' => (bool) env('JWT_COOKIE_SECURE', true),

    'same_site' => env(
        'JWT_COOKIE_SAME_SITE',
        'lax'
    ),
];