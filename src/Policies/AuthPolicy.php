<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Policies;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Auth\Models\PersonalAccessToken;
use TheBachtiarz\Auth\Repositories\AuthUserRepository;
use TheBachtiarz\Auth\Repositories\PersonalAccessTokenRepository;
use TheBachtiarz\Auth\Traits\Attribute\UserModelAttributeTrait;

use function app;
use function array_merge;
use function assert;

class AuthPolicy
{
    use UserModelAttributeTrait;

    /**
     * Input identifier
     */
    protected string $identifier;

    /**
     * Input password
     */
    protected string $password;

    /**
     * Auth user token abilities
     */
    protected array|null $tokenAbilities = null;

    /**
     * Constructor
     */
    public function __construct(
        protected AuthUserRepository $authUserRepository,
        protected PersonalAccessTokenRepository $personalAccessTokenRepository,
    ) {
        if ($this->getUserModel()) {
            return;
        }

        $this->setUserModel(app(AuthUser::class));
    }

    // ? Public Methods

    /**
     * Create new session user
     */
    public function createSession(): AbstractAuthUser
    {
        $authUser = $this->authUserRepository->setUserModel($this->getUserModel())->getByIdentifier($this->getIdentifier());
        assert($authUser instanceof AbstractAuthUser);

        Auth::login(user: $authUser, remember: true);

        return authuser($authUser);
    }

    /**
     * Create new token user
     */
    public function createToken(): NewAccessToken
    {
        $userSession = $this->createSession();
        assert($userSession instanceof AbstractAuthUser);

        $token = $this->personalAccessTokenRepository->setCurrentUser($userSession)->createToken($this->getTokenAbilities() ?? ['*']);
        assert($token instanceof NewAccessToken);

        return $token;
    }

    /**
     * Delete session
     */
    public function deleteSession(): bool
    {
        if (! Auth::hasUser()) {
            throw new AuthenticationException();
        }

        Auth::logoutCurrentDevice();

        return true;
    }

    /**
     * Delete token by name
     */
    public function deleteTokenByName(string $tokenName): bool
    {
        if (! Auth::hasUser()) {
            throw new AuthenticationException();
        }

        return $this->personalAccessTokenRepository->deleteByName($tokenName);
    }

    /**
     * Convert new access token to array
     *
     * @return array
     */
    public function tokenToArray(NewAccessToken $newAccessToken): array
    {
        $tokenEntity = $newAccessToken->accessToken;
        assert($tokenEntity instanceof PersonalAccessToken);

        return array_merge(
            $tokenEntity->simpleListMap(),
            [
                'token' => $newAccessToken->plainTextToken,
            ],
        );
    }

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get identifier
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Get password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get token abilities
     */
    public function getTokenAbilities(): array|null
    {
        return $this->tokenAbilities;
    }

    // ? Setter Modules

    /**
     * Set identifier
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Set password
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set token abilities
     */
    public function setTokenAbilities(array|null $tokenAbilities = null): self
    {
        $this->tokenAbilities = $tokenAbilities;

        return $this;
    }
}
