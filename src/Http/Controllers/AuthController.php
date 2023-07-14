<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use TheBachtiarz\Auth\Http\Requests\API\IdentifierRequest;
use TheBachtiarz\Auth\Http\Requests\API\TokenCreateRequest;
use TheBachtiarz\Auth\Http\Requests\API\TokenNameRequest;
use TheBachtiarz\Auth\Http\Requests\API\UserResetPasswordRequest;
use TheBachtiarz\Auth\Http\Requests\Rules\IdentifierRule;
use TheBachtiarz\Auth\Http\Requests\Rules\PasswordRule;
use TheBachtiarz\Auth\Http\Requests\Rules\TokenNameRule;
use TheBachtiarz\Auth\Http\Requests\Rules\UserResetPasswordRule;
use TheBachtiarz\Auth\Services\PersonalAccessTokenService;
use TheBachtiarz\Auth\Services\TokenResetService;
use TheBachtiarz\Base\App\Controllers\AbstractController;

class AuthController extends AbstractController
{
    /**
     * Constructor
     */
    public function __construct(
        protected PersonalAccessTokenService $personalAccessTokenService,
        protected TokenResetService $tokenResetService,
    ) {
        parent::__construct();
    }

    /**
     * Create user token
     */
    public function login(TokenCreateRequest $request): JsonResponse
    {
        $this->personalAccessTokenService->createToken(
            identifier: $request->get(key: IdentifierRule::INPUT_IDENTIFIER),
            password: $request->get(key: PasswordRule::INPUT_PASSWORD),
        );

        return $this->getResult();
    }

    /**
     * Get list user token
     */
    public function listToken(): JsonResponse
    {
        $this->personalAccessTokenService->getTokenList();

        return $this->getResult();
    }

    /**
     * Delete user token
     */
    public function logout(TokenNameRequest $request): JsonResponse
    {
        $this->personalAccessTokenService->deleteToken(
            tokenName: $request->get(key: TokenNameRule::INPUT_NAME),
        );

        return $this->getResult();
    }

    /**
     * Delete all user token
     */
    public function revoke(): JsonResponse
    {
        $this->personalAccessTokenService->revokeTokens();

        return $this->getResult();
    }

    /**
     * Create user token reset password
     */
    public function createTokenResetPassword(IdentifierRequest $request): JsonResponse
    {
        $this->tokenResetService->createTokenResetPassword(
            identifier: $request->get(key: IdentifierRule::INPUT_IDENTIFIER),
        );

        return $this->getResult();
    }

    /**
     * Execute user reset password
     */
    public function executeUserResetPassword(UserResetPasswordRequest $request): JsonResponse
    {
        $this->tokenResetService->resetUserPassword(
            token: $request->get(key: UserResetPasswordRule::INPUT_TOKEN),
            newPassword: $request->get(key: UserResetPasswordRule::INPUT_NEWPASSWORD),
        );

        return $this->getResult();
    }
}
