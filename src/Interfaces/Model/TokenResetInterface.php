<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Interfaces\Model;

use Illuminate\Support\Carbon;
use TheBachtiarz\Base\App\Interfaces\Model\AbstractModelInterface;

interface TokenResetInterface extends AbstractModelInterface
{
    /**
     * Table name
     */
    public const TABLE_NAME = 'token_resets';

    /**
     * Model attributes
     */
    public const ATTRIBUTE_FILLABLE = [
        self::ATTRIBUTE_TOKEN,
        self::ATTRIBUTE_IDENTIFIER,
        self::ATTRIBUTE_EXPIRESAT,
    ];

    public const ATTRIBUTE_TOKEN      = 'token';
    public const ATTRIBUTE_IDENTIFIER = 'identifier';
    public const ATTRIBUTE_EXPIRESAT  = 'expires_at';

    // ? Getter Modules

    /**
     * Get token reset
     */
    public function getToken(): string|null;

    /**
     * Get identifier
     */
    public function getIdentifier(): string|null;

    /**
     * Get expires at
     */
    public function getExpiresAt(): string|null;

    // ? Setter Modules

    /**
     * Set token reset
     */
    public function setToken(string $token): self;

    /**
     * Set identifier
     */
    public function setIdentifier(string $identifier): self;

    /**
     * Set expires at
     */
    public function setExpiresAt(Carbon $expiresAt): self;
}
