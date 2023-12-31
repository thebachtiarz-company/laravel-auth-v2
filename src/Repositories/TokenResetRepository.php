<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use TheBachtiarz\Auth\Interfaces\Models\TokenResetInterface;
use TheBachtiarz\Auth\Models\TokenReset;
use TheBachtiarz\Base\App\Repositories\AbstractRepository;

use function app;
use function assert;
use function sprintf;

class TokenResetRepository extends AbstractRepository
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modelEntity = app(TokenReset::class);

        parent::__construct();
    }

    // ? Public Methods

    /**
     * Get by token
     */
    public function getByToken(string $token): TokenResetInterface
    {
        $this->modelBuilder(modelBuilder: TokenReset::getByToken($token));

        $entity = $this->modelBuilder()->first();
        assert($entity instanceof TokenResetInterface);

        if (! $entity && $this->throwIfNullEntity()) {
            throw new ModelNotFoundException(sprintf("Token reset with token '%s' not found", $token));
        }

        return $entity;
    }

    /**
     * Get by identifier
     *
     * @return Collection<TokenResetInterface>
     */
    public function getByIdentifier(string $identifier): Collection
    {
        $this->modelBuilder(modelBuilder: TokenReset::getByIdentifier($identifier));

        if (! $this->modelBuilder()->count() && $this->throwIfNullEntity()) {
            throw new ModelNotFoundException(sprintf("Cannot find any token reset with identifier '%s'", $identifier));
        }

        return $this->modelBuilder()->get();
    }

    /**
     * Create new token reset
     */
    public function create(TokenResetInterface $tokenResetInterface): TokenResetInterface
    {
        /** @var Model $tokenResetInterface */
        $create = $this->createFromModel($tokenResetInterface);
        assert($create instanceof TokenResetInterface);

        if (! $create) {
            throw new Exception('Failed to create new token reset');
        }

        return $create;
    }

    /**
     * Update current token reset
     */
    public function save(TokenResetInterface $tokenResetInterface): TokenResetInterface
    {
        /** @var Model|TokenResetInterface $tokenResetInterface */
        $save = $tokenResetInterface->save();

        if (! $save) {
            throw new Exception('Failed to save current token reset');
        }

        return $tokenResetInterface;
    }

    /**
     * Delete by token
     */
    public function deleteByToken(string $token): bool
    {
        $tokenReset = $this->getByToken($token);

        if (! $tokenReset) {
            throw new ModelNotFoundException('Failed to delete token reset');
        }

        return $this->deleteById($tokenReset->getId());
    }

    /**
     * Delete by identifier
     */
    public function deleteByIdentifier(string $identifier): bool
    {
        /** @var Collection<TokenResetInterface> $collection */
        $collection = $this->getByIdentifier($identifier);

        foreach ($collection->all() ?? [] as $key => $tokenReset) {
            assert($tokenReset instanceof TokenResetInterface);
            $this->deleteById($tokenReset->getId());
        }

        return true;
    }

    // ? Protected Methods

    protected function getByIdErrorMessage(): string|null
    {
        return "Token reset with id '%s' not found";
    }

    protected function createOrUpdateErrorMessage(): string|null
    {
        return 'Failed to %s token reset';
    }

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
