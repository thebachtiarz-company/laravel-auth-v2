<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Models;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use TheBachtiarz\Auth\Interfaces\Models\PersonalAccessTokenInterface;
use TheBachtiarz\Auth\Models\AbstractAuthUser;

use function array_merge;

/**
 * Personal Access Token Scope Trait
 */
trait PersonalAccessTokenScopeTrait
{
    // ? Public Methods

    /**
     * Current auth user entity
     */
    private AbstractAuthUser $currentUserEntity;

    /**
     * Get list token by user
     *
     * @param array $whereCOnditions
     */
    public function scopeGetByUser(
        EloquentBuilder|QueryBuilder $builder,
        AbstractAuthUser $abstractAuthUser,
        array $whereCOnditions = [],
    ): BuilderContract {
        $this->currentUserEntity = $abstractAuthUser;

        return $builder->where($this->whereConditionResolver($whereCOnditions));
    }

    /**
     * Get token by user and name
     */
    public function scopeGetByUserName(
        EloquentBuilder|QueryBuilder $builder,
        AbstractAuthUser $abstractAuthUser,
        string $tokenName,
    ): BuilderContract {
        return $builder->getByUser(
            $abstractAuthUser,
            [PersonalAccessTokenInterface::ATTRIBUTE_NAME => $tokenName],
        );
    }

    // ? Private Methods

    /**
     * Where condition resolver
     *
     * @param array $whereConditionCustom default: []
     *
     * @return array
     */
    private function whereConditionResolver(array $whereConditionCustom = []): array
    {
        return array_merge(
            [
                PersonalAccessTokenInterface::ATTRIBUTE_TOKENABLETYPE => $this->currentUserEntity::class,
                PersonalAccessTokenInterface::ATTRIBUTE_TOKENABLEID => $this->currentUserEntity->getId(),
            ],
            $whereConditionCustom,
        );
    }
}
