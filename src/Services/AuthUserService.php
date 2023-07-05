<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Services;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use TheBachtiarz\Auth\Interfaces\Model\AuthUserInterface;
use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Auth\Repositories\AuthUserRepository;
use TheBachtiarz\Auth\Repositories\PersonalAccessTokenRepository;
use TheBachtiarz\Auth\Traits\Attribute\UserModelAttributeTrait;
use TheBachtiarz\Base\App\Helpers\CarbonHelper;
use TheBachtiarz\Base\App\Services\AbstractService;
use Throwable;

use function app;
use function array_merge;
use function assert;
use function mb_strlen;
use function sprintf;

class AuthUserService extends AbstractService
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

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->setUserModel(null);
    }

    // ? Public Methods

    /**
     * Create new user
     *
     * @return array
     */
    public function createNewUser(string $identifier, string $password): array
    {
        try {
            // check if identifier has been already exist
            if ($this->checkIsUserIdentifierExist($identifier)) {
                throw new Exception(sprintf(
                    "Oops!!, the %s '%s' already been taken",
                    $this->getIdentifierMethod(),
                    $identifier,
                ));
            }

            $newUser = $this->createUserProcess(identifier: $identifier, password: $password);
            assert($newUser instanceof AbstractAuthUser);

            // send mail notification
            if ($this->getIdentifierMethod() === AuthUserInterface::ATTRIBUTE_EMAIL) {
                $this->sendMailNotification($newUser);
            }

            // get result as map
            $result = $this->returnResultCreateUserAsMap($newUser);

            $this->setResponseData(message: 'Successfully create new user', data: $result, httpCode: 201);

            return $this->serviceResult(status: true, message: 'Successfully create new user', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    /**
     * Verify email after create new user
     *
     * @return array
     */
    public function verifyEmail(string $email): array
    {
        try {
            if ($this->getIdentifierMethod() !== AuthUserInterface::ATTRIBUTE_EMAIL) {
                throw new Exception('Email verification not found!');
            }

            $authUser = $this->authUserRepository->setUserModel($this->getUserModel())->getByIdentifier($email);
            assert($authUser instanceof AuthUserInterface);
            $authUser->setEmailVerifiedAt(CarbonHelper::dbDateTime());

            assert($authUser instanceof AbstractAuthUser);
            $update = $this->authUserRepository->setUserModel($this->getUserModel())->save($authUser);

            $result = $update->simpleListMap();

            $this->setResponseData(message: 'Successfully verify email', data: $result, httpCode: 201);

            return $this->serviceResult(status: true, message: 'Successfully verify email', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    /**
     * Update auth user password
     *
     * @return array
     */
    public function updatePassword(string $identifier, string $currentPassword, string $newPassword): array
    {
        try {
            $authUser = $this->authUserRepository->setUserModel($this->getUserModel())->getByIdentifier($identifier);
            assert($authUser instanceof AuthUserInterface);

            // Verify current password
            $verifyCurrentPassword = Hash::check(
                value: $currentPassword,
                hashedValue: $authUser->getPassword(),
            );

            if (! $verifyCurrentPassword) {
                throw new Exception('Failed to verify current password');
            }

            $authUser->setPassword(password: $newPassword, hashed: true);

            assert($authUser instanceof AbstractAuthUser);

            // Update password
            $update = $this->authUserRepository->setUserModel($this->getUserModel())->save($authUser);

            // Revoke all token belongs to user
            $this->personalAccessTokenRepository->setCurrentUser($authUser)->deleteByUser();

            $result = $update->simpleListMap();

            $this->setResponseData(message: 'Successfully update password', data: $result, httpCode: 201);

            return $this->serviceResult(status: true, message: 'Successfully update password', data: $result);
        } catch (Throwable $th) {
            $this->log($th);
            $this->setResponseData(message: $th->getMessage(), status: 'error', httpCode: 202);

            return $this->serviceResult(message: $th->getMessage());
        }
    }

    // ? Protected Methods

    /**
     * Check is user identifier exist
     */
    protected function checkIsUserIdentifierExist(string $identifier): bool
    {
        return ! ! $this->getUserModel()::getByIdentifier($identifier)->first();
    }

    /**
     * Create user process
     *
     * @throws Exception
     */
    protected function createUserProcess(string $identifier, string $password): AbstractAuthUser
    {
        $userPrepare = app(AuthUser::class);
        assert($userPrepare instanceof AuthUserInterface);
        $userPrepare->setIdentifier($identifier);
        $userPrepare->setPassword($password);

        assert($userPrepare instanceof AbstractAuthUser);

        return $this->authUserRepository->setUserModel($this->getUserModel())->create($userPrepare);
    }

    /**
     * Send email create user notification
     */
    protected function sendMailNotification(AbstractAuthUser $abstractAuthUser): bool
    {
        try {
            if ($this->getIdentifierMethod() === AuthUserInterface::ATTRIBUTE_EMAIL) {
                /** @var AuthUserInterface|AbstractAuthUser $userPrepare */
            }

            return true;
        } catch (Throwable $th) {
            $this->log($th);

            return false;
        }
    }

    /**
     * Get result create new user as map
     *
     * @return array
     */
    protected function returnResultCreateUserAsMap(AbstractAuthUser $abstractAuthUser): array
    {
        /** @var AuthUserInterface|AbstractAuthUser $abstractAuthUser */
        return array_merge(
            $abstractAuthUser->simpleListMap(),
            [
                AuthUserInterface::ATTRIBUTE_PASSWORD => Str::mask(
                    string: $abstractAuthUser->getPassword(true),
                    character: '*',
                    index: 2,
                    length: mb_strlen($abstractAuthUser->getPassword(true)) - (2 + 2),
                ),
            ],
        );
    }

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
