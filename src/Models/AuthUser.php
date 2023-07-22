<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\Auth\Traits\Models\AuthUserMapTrait;
use TheBachtiarz\Auth\Traits\Models\AuthUserScopeTrait;
use TheBachtiarz\Base\App\Helpers\CarbonHelper;
use TheBachtiarz\Base\App\Helpers\TemporaryDataHelper;

use function authidentifiermethod;

class AuthUser extends AbstractAuthUser implements AuthUserInterface
{
    use SoftDeletes;
    use AuthUserScopeTrait;
    use AuthUserMapTrait;

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

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get identifier
     */
    public function getIdentifier(): string|null
    {
        return $this->__get(authidentifiermethod());
    }

    /**
     * Get email verified at
     */
    public function getEmailVerifiedAt(): string|null
    {
        return $this->__get(self::ATTRIBUTE_EMAIL_VERIFIED_AT);
    }

    /**
     * Get password
     *
     * @param bool|null $unHashed If true, will return un-hashed password when create new user
     */
    public function getPassword(bool|null $unHashed = false): string|null
    {
        return $unHashed ? TemporaryDataHelper::getData(self::TEMP_UNHASHED_PASSWORD) : $this->__get(self::ATTRIBUTE_PASSWORD);
    }

    /**
     * Get token expires at
     */
    public function getTokenExpiresAt(): string|null
    {
        return $this->tokenExpiresAt ? CarbonHelper::anyConvDateToTimestamp(datetime: $this->tokenExpiresAt) : null;
    }

    // ? Setter Modules

    /**
     * Set identifier
     */
    public function setIdentifier(string $identifier): self
    {
        $this->__set(authidentifiermethod(), $identifier);

        return $this;
    }

    /**
     * Set email verified at
     */
    public function setEmailVerifiedAt(string $emailVerifiedAt): self
    {
        $this->__set(self::ATTRIBUTE_EMAIL_VERIFIED_AT, $emailVerifiedAt);

        return $this;
    }

    /**
     * Set password
     */
    public function setPassword(string $password, bool|null $hashed = true): self
    {
        $this->__set(self::ATTRIBUTE_PASSWORD, $hashed ? Hash::make($password) : $password);
        TemporaryDataHelper::addData(self::TEMP_UNHASHED_PASSWORD, $password);

        return $this;
    }

    /**
     * Set token expires at
     *
     * @param int|null $expiredAfterMinutes Default: 60 minutes
     */
    public function setTokenExpired(int|null $expiredAfterMinutes = 60): self
    {
        $this->tokenExpiresAt = CarbonHelper::dbGetFullDateAddMinutes(minutes: $expiredAfterMinutes);

        return $this;
    }
}
