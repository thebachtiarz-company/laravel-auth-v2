<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Models;

use Illuminate\Support\Carbon;
use TheBachtiarz\Auth\Interfaces\Model\TokenResetInterface;
use TheBachtiarz\Auth\Traits\Model\TokenResetScopeTrait;
use TheBachtiarz\Base\App\Models\AbstractModel;

class TokenReset extends AbstractModel implements TokenResetInterface
{
    use TokenResetScopeTrait;

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
     * Get token reset
     */
    public function getToken(): string|null
    {
        return $this->__get(self::ATTRIBUTE_TOKEN);
    }

    /**
     * Get identifier
     */
    public function getIdentifier(): string|null
    {
        return $this->__get(self::ATTRIBUTE_IDENTIFIER);
    }

    /**
     * Get expires at
     */
    public function getExpiresAt(): Carbon|string|null
    {
        return $this->__get(self::ATTRIBUTE_EXPIRESAT);
    }

    // ? Setter Modules

    /**
     * Set token reset
     */
    public function setToken(string $token): self
    {
        $this->__set(self::ATTRIBUTE_TOKEN, $token);

        return $this;
    }

    /**
     * Set identifier
     */
    public function setIdentifier(string $identifier): self
    {
        $this->__set(self::ATTRIBUTE_IDENTIFIER, $identifier);

        return $this;
    }

    /**
     * Set expires at
     */
    public function setExpiresAt(Carbon|string $expiresAt): self
    {
        $this->__set(self::ATTRIBUTE_EXPIRESAT, $expiresAt);

        return $this;
    }
}
