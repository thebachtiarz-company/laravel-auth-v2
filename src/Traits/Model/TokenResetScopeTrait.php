<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Model;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use TheBachtiarz\Auth\Interfaces\Model\TokenResetInterface;

/**
 * Token Reset Scope Trait
 */
trait TokenResetScopeTrait
{
    /**
     * Get by token
     */
    public function scopeGetByToken(EloquentBuilder|QueryBuilder $builder, string $token): BuilderContract
    {
        $attribute = TokenResetInterface::ATTRIBUTE_TOKEN;

        return $builder->where(DB::raw("BINARY `$attribute`"), $token);
    }

    /**
     * Get by identifier
     */
    public function scopeGetByIdentifier(EloquentBuilder|QueryBuilder $builder, string $identifier): BuilderContract
    {
        $attribute = TokenResetInterface::ATTRIBUTE_IDENTIFIER;

        return $builder->where(DB::raw("BINARY `$attribute`"), $identifier);
    }
}
