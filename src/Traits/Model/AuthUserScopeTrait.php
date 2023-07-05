<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Model;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

/**
 * Auth User Scope Trait
 */
trait AuthUserScopeTrait
{
    /**
     * Get by identifier
     */
    public function scopeGetByIdentifier(EloquentBuilder|QueryBuilder $builder, string $identifier): BuilderContract
    {
        $attribute = authidentifiermethod();

        return $builder->where(DB::raw("BINARY `$attribute`"), $identifier);
    }
}
