<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use TheBachtiarz\Auth\Http\Controllers\AuthController;
use TheBachtiarz\Auth\Http\Controllers\UserController;

/**
 * Group    : auth
 * URI      : {{base_url}}/{{app_prefix}}/auth/---
 */
Route::prefix('auth')->middleware('api')->controller(AuthController::class)->group(static function (): void {
    /**
     * Name     : User token create
     * Method   : POST
     * Url      : {{base_url}}/{{app_prefix}}/auth/token-create
     */
    Route::post('token-create', 'login');

    /**
     * Name     : User token list
     * Method   : POST
     * Url      : {{base_url}}/{{app_prefix}}/auth/token-list
     */
    Route::post('token-list', 'listToken')->middleware('auth:sanctum');

    /**
     * Name     : User token delete
     * Method   : POST
     * Url      : {{base_url}}/{{app_prefix}}/auth/token-delete
     */
    Route::post('token-delete', 'logout')->middleware('auth:sanctum');

    /**
     * Name     : User token revoke
     * Method   : POST
     * Url      : {{base_url}}/{{app_prefix}}/auth/token-revoke
     */
    Route::post('token-revoke', 'revoke')->middleware('auth:sanctum');

    /**
     * Group    : auth/password-reset
     * URI      : {{base_url}}/{{app_prefix}}/auth/password-reset/---
     */
    Route::prefix('password-reset')->group(static function (): void {
        /**
         * Name     : Generate user password reset token
         * Method   : POST
         * Url      : {{base_url}}/{{app_prefix}}/auth/password-reset/generate-token
         */
        Route::post('generate-token', 'createTokenResetPassword');

        /**
         * Name     : Execute user password reset
         * Method   : POST
         * Url      : {{base_url}}/{{app_prefix}}/auth/password-reset/execute
         */
        Route::post('execute', 'executeUserResetPassword');
    });
});

/**
 * Group    : user
 * URI      : {{base_url}}/{{app_prefix}}/user/---
 */
Route::prefix('user')->middleware('api')->controller(UserController::class)->group(static function (): void {
    /**
     * Name     : User create
     * Method   : POST
     * Url      : {{base_url}}/{{app_prefix}}/user/create
     */
    Route::post('create', 'createUser');

    /**
     * Name     : User email verification
     * Method   : POST
     * Url      : {{base_url}}/{{app_prefix}}/user/email-verify
     */
    Route::post('email-verify', 'verifyEmail');

    /**
     * Name     : User password update
     * Method   : POST
     * Url      : {{base_url}}/{{app_prefix}}/user/password-update
     */
    Route::post('password-update', 'updatePassword');
});
