<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder as QueryBuilder;
use TheBachtiarz\Auth\Interfaces\Model\TokenResetInterface;
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
     * Get by id
     */
    public function getById(int $id): TokenResetInterface
    {
        $entity = TokenReset::find($id);
        assert($entity instanceof TokenResetInterface);

        if (! $entity) {
            throw new ModelNotFoundException(sprintf("Token reset with id '%s' not found", $id));
        }

        return $entity;
    }

    /**
     * Get by token
     */
    public function getByToken(string $token): TokenResetInterface
    {
        $entity = TokenReset::getByToken($token);
        assert($entity instanceof TokenResetInterface);

        if (! $entity) {
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
        $builder = TokenReset::getByIdentifier($identifier);
        assert($builder instanceof EloquentBuilder || $builder instanceof QueryBuilder);

        if (! $builder->count()) {
            throw new ModelNotFoundException(sprintf("Cannot find any token reset with identifier '%s'", $identifier));
        }

        return $builder->get();
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
     * Delete by id
     */
    public function deleteById(int $id): bool
    {
        $tokenReset = $this->getById($id);
        assert($tokenReset instanceof Model);

        return $tokenReset?->delete();
    }

    /**
     * Delete by token
     */
    public function deleteByToken(string $token): bool
    {
        $tokenReset = $this->getByToken($token);

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

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
