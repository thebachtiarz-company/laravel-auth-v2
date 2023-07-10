<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Policies;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\Auth\Interfaces\Model\AuthUserInterface;
use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Auth\Models\PersonalAccessToken;
use TheBachtiarz\Auth\Repositories\AuthUserRepository;
use TheBachtiarz\Auth\Repositories\PersonalAccessTokenRepository;
use TheBachtiarz\Auth\Traits\Attribute\UserModelAttributeTrait;

use function app;
use function array_merge;
use function assert;
use function sprintf;

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
        Auth::attempt(
            credentials: [
                authidentifiermethod() => $this->getIdentifier(),
                AuthUserInterface::ATTRIBUTE_PASSWORD => $this->getPassword(),
            ],
            remember: true,
        );

        if (! Auth::hasUser()) {
            throw new Exception('Credential not found!');
        }

        return authuser($this->getUserModel());
    }

    /**
     * Create new token user
     */
    public function createToken(): NewAccessToken
    {
        $userSession = $this->createSession();
        assert($userSession instanceof AbstractAuthUser);

        if (tbauthconfig(keyName: 'single_device_only', useOrigin: false)) {
            $userSession->tokens()->delete();
        }

        if (tbauthconfig(keyName: 'limit_user_login', useOrigin: false)) {
            $userTokenCount    = $userSession->tokens()->count();
            $tokenLimitAllowed = (int) tbauthconfig(keyName: 'max_user_login', useOrigin: false);

            if ($userTokenCount >= $tokenLimitAllowed) {
                throw new Exception(sprintf(
                    'User login has reached the limit of %s time(s)',
                    $tokenLimitAllowed,
                ));
            }
        }

        $token = $this->personalAccessTokenRepository->createToken($this->getTokenAbilities() ?? ['*']);
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
        $tokenEntity = new PersonalAccessToken($newAccessToken?->accessToken->toArray());
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
