<?php

declare(strict_types=1);

use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;

return [
    /*
    |--------------------------------------------------------------------------
    | User Authentication Identity Method
    |--------------------------------------------------------------------------
    |
    | Here are method identity for user authentication.
    | Available: [email, username].
    | example: email
    |
    */
    // 'user_auth_identity_method' => AuthUserInterface::ATTRIBUTE_USERNAME,
    'user_auth_identity_method' => AuthUserInterface::ATTRIBUTE_EMAIL,

    /*
    |--------------------------------------------------------------------------
    | Use route REST API
    |--------------------------------------------------------------------------
    |
    | Here are list the configuration for enable/disable REST API for auth.
    | If you prefer to use your own route, we suggest to disable it.
    |
    */
    'route_service' => true,

    /*
    |--------------------------------------------------------------------------
    | Default new user password
    |--------------------------------------------------------------------------
    |
    | Define default user password.
    |
    */
    'default_user_password' => '',

    /*
    |--------------------------------------------------------------------------
    | Single Login User Attempt
    |--------------------------------------------------------------------------
    |
    | Define is user can only login at one device per attempt.
    | The other device will be log outed automatically.
    |
    */
    'single_device_only' => false,

    /*
    |--------------------------------------------------------------------------
    | Limit Auth User Login
    |--------------------------------------------------------------------------
    |
    | Define auth user limit.
    |
    */
    'limit_user_login' => false,

    /*
    |--------------------------------------------------------------------------
    | Max Auth User Attempt
    |--------------------------------------------------------------------------
    |
    | Define maximum auth user attmpt(s).
    |
    */
    'max_user_login' => 5,
];
