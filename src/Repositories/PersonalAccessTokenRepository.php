<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Repositories;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\Auth\Interfaces\Models\PersonalAccessTokenInterface;
use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Auth\Models\PersonalAccessToken;
use TheBachtiarz\Base\App\Repositories\AbstractRepository;

use function app;
use function assert;
use function authuser;

class PersonalAccessTokenRepository extends AbstractRepository
{
    /**
     * Current user
     */
    protected AbstractAuthUser|null $currentUser = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modelEntity = app(PersonalAccessToken::class);

        parent::__construct();
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->setCurrentUser(null);
    }

    // ? Public Methods

    /**
     * Get by user
     *
     * @return Collection<PersonalAccessTokenInterface>
     */
    public function getByUser(): Collection
    {
        $userAuthenticated = $this->getUserAuthenticated();

        $this->modelBuilder(modelBuilder: PersonalAccessToken::getByUser($userAuthenticated));

        return $this->modelBuilder()->get();
    }

    /**
     * Get by user and token name
     */
    public function getByName(string $tokenName): PersonalAccessTokenInterface|null
    {
        $userAuthenticated = $this->getUserAuthenticated();

        $this->modelBuilder(modelBuilder: PersonalAccessToken::getByUserName($userAuthenticated, $tokenName));

        return $this->modelBuilder()->first();
    }

    /**
     * Create user auth token
     *
     * @param array $abilities
     */
    public function createToken(array $abilities = ['*']): NewAccessToken
    {
        $userAuthenticated = $this->getUserAuthenticated();

        return $userAuthenticated->createToken(
            name: Str::uuid()->toString(),
            abilities: $abilities,
            expiresAt: $userAuthenticated->getTokenExpiresAt(),
        );
    }

    /**
     * Delete auth token by name
     */
    public function deleteByName(string $tokenName): bool
    {
        $token = $this->getByName($tokenName);
        assert($token instanceof PersonalAccessToken || $token === null);

        return $token?->delete() ?? false;
    }

    /**
     * Revoke all token belings to user
     */
    public function deleteByUser(): bool
    {
        /** @var Collection<PersonalAccessTokenInterface> $collection */
        $collection = $this->getByUser();

        foreach ($collection->all() ?? [] as $key => $token) {
            assert($token instanceof PersonalAccessTokenInterface);
            $this->deleteByName($token->getName());
        }

        return true;
    }

    // ? Protected Methods

    /**
     * Get user authenticated
     */
    protected function getUserAuthenticated(): AbstractAuthUser
    {
        if ($this->currentUser) {
            return $this->currentUser;
        }

        if (! Auth::hasUser()) {
            throw new AuthenticationException();
        }

        $this->currentUser = authuser(app(AuthUser::class));

        return $this->currentUser;
    }

    protected function getByIdErrorMessage(): string|null
    {
        return "Token with id '%s' not found!";
    }

    protected function createOrUpdateErrorMessage(): string|null
    {
        return 'Failed to %s token';
    }

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get current user
     */
    public function getCurrentUser(): AbstractAuthUser|null
    {
        return $this->currentUser;
    }

    // ? Setter Modules

    /**
     * Set current user
     */
    public function setCurrentUser(AbstractAuthUser|null $currentUser = null): self
    {
        $this->currentUser = $currentUser;

        return $this;
    }
}
