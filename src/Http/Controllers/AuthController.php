<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use TheBachtiarz\Auth\Http\Requests\API\TokenCreateRequest;
use TheBachtiarz\Auth\Http\Requests\API\TokenNameRequest;
use TheBachtiarz\Auth\Http\Requests\Rules\TokenCreateRule;
use TheBachtiarz\Auth\Http\Requests\Rules\TokenNameRule;
use TheBachtiarz\Auth\Services\PersonalAccessTokenService;
use TheBachtiarz\Base\App\Controllers\AbstractController;

class AuthController extends AbstractController
{
    /**
     * Constructor
     */
    public function __construct(
        protected PersonalAccessTokenService $personalAccessTokenService,
    ) {
        parent::__construct();
    }

    /**
     * Create user token
     */
    public function login(TokenCreateRequest $request): JsonResponse
    {
        $this->personalAccessTokenService->createToken(
            identifier: $request->get(key: TokenCreateRule::INPUT_IDENTIFIER),
            password: $request->get(key: TokenCreateRule::INPUT_PASSWORD),
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
}
