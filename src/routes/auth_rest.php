<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use TheBachtiarz\Auth\Http\Controllers\AuthController;

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
});
