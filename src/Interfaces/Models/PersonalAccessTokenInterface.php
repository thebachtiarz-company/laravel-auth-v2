<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Interfaces\Models;

use Illuminate\Support\Carbon;
use TheBachtiarz\Base\App\Interfaces\Models\AbstractModelInterface;

interface PersonalAccessTokenInterface extends AbstractModelInterface
{
    /**
     * Table name
     */
    public const TABLE_NAME = 'personal_access_tokens';

    /**
     * Model attributes
     */
    public const ATTRIBUTE_FILLABLE = [
        self::ATTRIBUTE_NAME,
        self::ATTRIBUTE_TOKEN,
        self::ATTRIBUTE_ABILITIES,
        self::ATTRIBUTE_EXPIRESAT,
    ];

    public const ATTRIBUTE_TOKENABLETYPE = 'tokenable_type';
    public const ATTRIBUTE_TOKENABLEID   = 'tokenable_id';
    public const ATTRIBUTE_NAME          = 'name';
    public const ATTRIBUTE_TOKEN         = 'token';
    public const ATTRIBUTE_ABILITIES     = 'abilities';
    public const ATTRIBUTE_LASTUSEDAT    = 'last_used_at';
    public const ATTRIBUTE_EXPIRESAT     = 'expires_at';

    // ? Getter Modules

    /**
     * Get tokenable type
     */
    public function getTokenableType(): string|null;

    /**
     * Get tokenable id
     */
    public function getTokenableId(): int|null;

    /**
     * Get name
     */
    public function getName(): string|null;

    /**
     * Get token
     */
    public function getToken(): string|null;

    /**
     * Get abilities
     */
    public function getAbilities(): array|null;

    /**
     * Get last used at
     */
    public function getLastUsedAt(): Carbon|string|null;

    /**
     * Get expires at
     */
    public function getExpiresAt(): Carbon|string|null;

    // ? Setter Modules

    /**
     * Set tokenable type
     */
    public function setTokenableType(string $tokenableType): self;

    /**
     * tokenable id
     */
    public function setTokenableId(int $tokenableId): self;

    /**
     * Set name
     */
    public function setName(string $name): self;

    /**
     * Set token
     */
    public function setToken(string $token): self;

    /**
     * Set abilities
     *
     * @param array $abilities
     */
    public function setAbilities(array $abilities): self;

    /**
     * Set last used at
     */
    public function setLastUsedAt(Carbon|string $lastUsedAt): self;

    /**
     * Set expires at
     */
    public function setExpiresAt(Carbon|string $expiresAt): self;
}
