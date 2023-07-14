<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use TheBachtiarz\Auth\Http\Requests\API\EmailVerificationRequest;
use TheBachtiarz\Auth\Http\Requests\API\PasswordUpdateRequest;
use TheBachtiarz\Auth\Http\Requests\API\UserCreateRequest;
use TheBachtiarz\Auth\Http\Requests\Rules\EmailVerificationRule;
use TheBachtiarz\Auth\Http\Requests\Rules\IdentifierRule;
use TheBachtiarz\Auth\Http\Requests\Rules\PasswordRule;
use TheBachtiarz\Auth\Services\AuthUserService;
use TheBachtiarz\Base\App\Controllers\AbstractController;

class UserController extends AbstractController
{
    /**
     * Constructor
     */
    public function __construct(
        protected AuthUserService $authUserService,
    ) {
        parent::__construct();
    }

    // ? Public Methods

    /**
     * User create
     */
    public function createUser(UserCreateRequest $request): JsonResponse
    {
        $this->authUserService->createNewUser(
            identifier: $request->get(key: IdentifierRule::INPUT_IDENTIFIER),
            password: $request->get(key: PasswordRule::INPUT_PASSWORD),
        );

        return $this->getResult();
    }

    /**
     * User email verification
     */
    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        $this->authUserService->verifyEmail(
            email: $request->get(key: EmailVerificationRule::INPUT_EMAIL),
        );

        return $this->getResult();
    }

    /**
     * User password update
     */
    public function updatePassword(PasswordUpdateRequest $request): JsonResponse
    {
        $this->authUserService->updatePassword(
            identifier: $request->get(key: 'identifier'),
            currentPassword: $request->get(key: 'currentPassword'),
            newPassword: $request->get(key: 'newPassword'),
        );

        return $this->getResult();
    }

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
