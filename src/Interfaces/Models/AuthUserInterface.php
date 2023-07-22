<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Interfaces\Models;

use TheBachtiarz\Base\App\Interfaces\Models\AbstractModelInterface;

interface AuthUserInterface extends AbstractModelInterface
{
    /**
     * Table name
     */
    public const TABLE_NAME = 'auth_users';

    /**
     * Attribute fillable
     */
    public const ATTRIBUTE_FILLABLE = [
        self::ATTRIBUTE_USERNAME,
        self::ATTRIBUTE_EMAIL,
        self::ATTRIBUTE_EMAIL_VERIFIED_AT,
        self::ATTRIBUTE_PASSWORD,
    ];

    public const ATTRIBUTE_USERNAME          = 'username';
    public const ATTRIBUTE_EMAIL             = 'email';
    public const ATTRIBUTE_EMAIL_VERIFIED_AT = 'email_verified_at';
    public const ATTRIBUTE_PASSWORD          = 'password';

    public const TEMP_UNHASHED_PASSWORD = 'unhashed_password';

    // ? Getter Modules

    /**
     * Get identifier
     */
    public function getIdentifier(): string|null;

    /**
     * Get email verified at
     */
    public function getEmailVerifiedAt(): string|null;

    /**
     * Get password
     *
     * @param bool|null $unHashed If true, will return un-hashed password
     */
    public function getPassword(bool|null $unHashed = false): string|null;

    /**
     * Get token expires at
     */
    public function getTokenExpiresAt(): string|null;

    // ? Setter Modules

    /**
     * Set identifier
     */
    public function setIdentifier(string $identifier): self;

    /**
     * Set email verified at
     */
    public function setEmailVerifiedAt(string $emailVerifiedAt): self;

    /**
     * Set password
     */
    public function setPassword(string $password, bool|null $hashed = true): self;

    /**
     * Set token expires at
     *
     * @param int|null $expiredAfterMinutes Default: 60 minutes
     */
    public function setTokenExpired(int|null $expiredAfterMinutes = 60): self;
}
