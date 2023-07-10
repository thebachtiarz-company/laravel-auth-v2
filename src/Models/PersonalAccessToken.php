<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Models;

use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use TheBachtiarz\Auth\Interfaces\Model\PersonalAccessTokenInterface;
use TheBachtiarz\Auth\Traits\Model\PersonalAccessTokenMapTrait;
use TheBachtiarz\Auth\Traits\Model\PersonalAccessTokenScopeTrait;

class PersonalAccessToken extends SanctumPersonalAccessToken implements PersonalAccessTokenInterface
{
    use PersonalAccessTokenScopeTrait;
    use PersonalAccessTokenMapTrait;

    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(self::TABLE_NAME);
        $this->fillable(self::ATTRIBUTE_FILLABLE);

        parent::__construct($attributes);
    }

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

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get id
     */
    public function getId(): int|null
    {
        return $this->__get(self::ATTRIBUTE_ID);
    }

    /**
     * Get tokenable type
     */
    public function getTokenableType(): string|null
    {
        return $this->__get(self::ATTRIBUTE_TOKENABLETYPE);
    }

    /**
     * Get tokenable id
     */
    public function getTokenableId(): int|null
    {
        return $this->__get(self::ATTRIBUTE_TOKENABLEID);
    }

    /**
     * Get name
     */
    public function getName(): string|null
    {
        return $this->__get(self::ATTRIBUTE_NAME);
    }

    /**
     * Get token
     */
    public function getToken(): string|null
    {
        return $this->__get(self::ATTRIBUTE_TOKEN);
    }

    /**
     * Get abilities
     */
    public function getAbilities(): array|null
    {
        return $this->__get(self::ATTRIBUTE_ABILITIES);
    }

    /**
     * Get last used at
     */
    public function getLastUsedAt(): Carbon|null
    {
        return $this->__get(self::ATTRIBUTE_LASTUSEDAT);
    }

    /**
     * Get expires at
     */
    public function getExpiresAt(): Carbon|null
    {
        return $this->__get(self::ATTRIBUTE_EXPIRESAT);
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
     */
    public function setId(int $id): static
    {
        $this->__set(self::ATTRIBUTE_ID, $id);

        return $this;
    }

    /**
     * Set tokenable type
     */
    public function setTokenableType(string $tokenableType): self
    {
        $this->__set(self::ATTRIBUTE_TOKENABLETYPE, $tokenableType);

        return $this;
    }

    /**
     * tokenable id
     */
    public function setTokenableId(int $tokenableId): self
    {
        $this->__set(self::ATTRIBUTE_TOKENABLEID, $tokenableId);

        return $this;
    }

    /**
     * Set name
     */
    public function setName(string $name): self
    {
        $this->__set(self::ATTRIBUTE_NAME, $name);

        return $this;
    }

    /**
     * Set token
     */
    public function setToken(string $token): self
    {
        $this->__set(self::ATTRIBUTE_TOKEN, $token);

        return $this;
    }

    /**
     * Set abilities
     *
     * @param array $abilities
     */
    public function setAbilities(array $abilities): self
    {
        $this->__set(self::ATTRIBUTE_ABILITIES, $abilities);

        return $this;
    }

    /**
     * Set last used at
     */
    public function setLastUsedAt(Carbon $lastUsedAt): self
    {
        $this->__set(self::ATTRIBUTE_LASTUSEDAT, $lastUsedAt);

        return $this;
    }

    /**
     * Set expires at
     */
    public function setExpiresAt(Carbon $expiresAt): self
    {
        $this->__set(self::ATTRIBUTE_EXPIRESAT, $expiresAt);

        return $this;
    }
}
