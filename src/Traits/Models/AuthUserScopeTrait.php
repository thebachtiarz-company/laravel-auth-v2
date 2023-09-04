<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Models;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;

use function authidentifiermethod;

/**
 * Auth User Scope Trait
 */
trait AuthUserScopeTrait
{
    /**
     * Get by code
     */
    public function scopeGetByCode(EloquentBuilder|QueryBuilder $builder, string $code): BuilderContract
    {
        $attribute = AuthUserInterface::ATTRIBUTE_CODE;

        return $builder->where(
            column: DB::raw("BINARY `$attribute`"),
            operator: '=',
            value: $code,
        );
    }

    /**
     * Get by identifier
     */
    public function scopeGetByIdentifier(EloquentBuilder|QueryBuilder $builder, string $identifier): BuilderContract
    {
        $attribute = authidentifiermethod();

        return $builder->where(
            column: DB::raw("BINARY `$attribute`"),
            operator: '=',
            value: $identifier,
        );
    }
}
