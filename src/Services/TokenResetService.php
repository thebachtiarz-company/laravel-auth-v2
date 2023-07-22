<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\Auth\Interfaces\Models\TokenResetInterface;
use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Auth\Models\TokenReset;
use TheBachtiarz\Auth\Repositories\AuthUserRepository;
use TheBachtiarz\Auth\Repositories\PersonalAccessTokenRepository;
use TheBachtiarz\Auth\Repositories\TokenResetRepository;
use TheBachtiarz\Auth\Traits\Attributes\UserModelAttributeTrait;
use TheBachtiarz\Base\App\Helpers\CarbonHelper;
use TheBachtiarz\Base\App\Services\AbstractService;
use Throwable;

use function app;
use function assert;
use function authidentifiermethod;

class TokenResetService extends AbstractService
{
    use UserModelAttributeTrait;

    /**
     * Define identifier method
     */
    protected string|null $identifierMethod = null;

    /**
     * Constructor
     */
    public function __construct(
        protected AuthUserRepository $authUserRepository,
        protected TokenResetRepository $tokenResetRepository,
        protected PersonalAccessTokenRepository $personalAccessTokenRepository,
    ) {
        if (! $this->getUserModel()) {
            $this->setUserModel(app(AuthUser::class));
        }

        if ($this->getIdentifierMethod()) {
            return;
        }

        $this->setIdentifierMethod(authidentifiermethod());
    }

    // ? Public Methods

    /**
     * Create token reset password
     *
     * @return array
     */
    public function createTokenResetPassword(string $identifier): array
    {
        try {
            $authUser = $this->authUserRepository->setUserModel($this->getUserModel())->getByIdentifier($identifier);
            assert($authUser instanceof AuthUserInterface);

            $email = $authUser->getIdentifier();

            if ($this->getIdentifierMethod() === AuthUserInterface::ATTRIBUTE_USERNAME) {
                $email = $authUser->getData(AuthUserInterface::ATTRIBUTE_EMAIL);
            }

            if (! $email) {
                throw new Exception('Please do register an email first');
            }

            $tokenResetPrepare = app(TokenReset::class);
            assert($tokenResetPrepare instanceof TokenResetInterface);
            $tokenResetPrepare->setIdentifier($email);
            $tokenResetPrepare->setToken(Str::uuid()->toString());
            $tokenResetPrepare->setExpiresAt(CarbonHelper::dbGetFullDateAddHours());

            $create = $this->tokenResetRepository->create($tokenResetPrepare);
            assert($create instanceof TokenResetInterface);

            // TODO: Send an email notification here

            $result = [
                'email' => $create->getIdentifier(),
                'expired' => CarbonHelper::anyConvDateToTimestamp($create->getExpiresAt()),
            ];

            $this->setResponseData(message: 'Successfully create token reset password', data: $result, httpCode: 201);

            return $this->serviceResult(status: true, message: 'Successfully create token reset password', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    /**
     * Reset user password
     *
     * @return array
     */
    public function resetUserPassword(
        string $token,
        string $newPassword,
    ): array {
        try {
            $tokenReset = $this->tokenResetRepository->getByToken($token);
            assert($tokenReset instanceof TokenResetInterface);

            /**
             * Check is token has expired
             */
            if (
                CarbonHelper::anyConvDateToTimestamp() >=
                CarbonHelper::anyConvDateToTimestamp($tokenReset->getExpiresAt())
            ) {
                $this->tokenResetRepository->deleteById($tokenReset->getId());

                throw new Exception('Access for reset password already expired');
            }

            DB::beginTransaction();

            $authUser = $this->authUserRepository
                ->setUserModel($this->getUserModel())
                ->getByIdentifier($tokenReset->getIdentifier());
            assert($authUser instanceof AuthUserInterface || $authUser instanceof AbstractAuthUser);
            $authUser->setPassword($newPassword);

            $updateUser = $this->authUserRepository->save($authUser);

            $this->personalAccessTokenRepository->setCurrentUser($updateUser)->deleteByUser();
            $this->tokenResetRepository->deleteByIdentifier($tokenReset->getIdentifier());

            DB::commit();

            $result = $updateUser->simpleListMap();

            $this->setResponseData(message: 'Successfully update user password', data: $result, httpCode: 201);

            return $this->serviceResult(status: true, message: 'Successfully update user password', data: $result);
        } catch (Throwable $th) {
            DB::rollBack();
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get identifier method
     */
    public function getIdentifierMethod(): string|null
    {
        return $this->identifierMethod;
    }

    // ? Setter Modules

    /**
     * Set identifier method
     */
    public function setIdentifierMethod(string|null $identifierMethod = null): self
    {
        $this->identifierMethod = $identifierMethod;

        return $this;
    }
}
