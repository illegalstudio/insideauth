<?php

return [
    'use_inside_auth'    => env('INSIDE_AUTH_USE_INSIDE_AUTH', true),
    'require_valid_user' => env('INSIDE_AUTH_REQUIRE_VALID_USER', true),
    'functionalities'    => [
        'register'           => env('INSIDE_AUTH_FUNCTIONALITIES_REGISTER', true),
        'forgot_password'    => env('INSIDE_AUTH_FUNCTIONALITIES_FORGOT_PASSWORD', true),
        'email_verification' => env('INSIDE_AUTH_FUNCTIONALITIES_EMAIL_VERIFICATION', true),
        'user_profile'       => env('INSIDE_AUTH_FUNCTIONALITIES_USER_PROFILE', true),
    ]
];
