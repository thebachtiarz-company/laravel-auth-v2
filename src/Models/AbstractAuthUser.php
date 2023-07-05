<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Models;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use TheBachtiarz\Base\App\Interfaces\Model\AbstractModelInterface;

abstract class AbstractAuthUser extends User implements AbstractModelInterface
{
    use HasApiTokens;
    use Notifiable;

    // ? Public Methods

    /**
     * Get data.
     *
     * Get by attribute or return whole data.
     */
    public function getData(string $attribute): mixed
    {
        return $this->__get($attribute);
    }

    /**
     * Set data.
     *
     * Set data using attribute and value exist.
     *
     * @return static
     */
    public function setData(string $attribute, mixed $value): static
    {
        $this->__set($attribute, $value);

        return $this;
    }

    // ? Getter Modules

    /**
     * Get id
     */
    public function getId(): int|null
    {
        return $this->__get(self::ATTRIBUTE_ID);
    }

    /**
     * Get created at
     */
    public function getCreatedAt(): mixed
    {
        return $this->__get(self::ATTRIBUTE_CREATEDAT);
    }

    /**
     * Get updated at
     */
    public function getUpdatedAt(): mixed
    {
        return $this->__get(self::ATTRIBUTE_UPDATEDAT);
    }

    // ? Setter Modules

    /**
     * Set id
     *
     * @return static
     */
    public function setId(int $id): static
    {
        $this->__set(self::ATTRIBUTE_ID, $id);

        return $this;
    }

    // ? Scope Modules

    /**
     * Get by identifier
     */
    abstract public function scopeGetByIdentifier(EloquentBuilder|QueryBuilder $builder, string $identifier): BuilderContract;

    // ? Map Modules

    /**
     * Auth user simple list map
     *
     * @return array
     */
    abstract public function simpleListMap(): array;

    // ? Relation Modules
}
