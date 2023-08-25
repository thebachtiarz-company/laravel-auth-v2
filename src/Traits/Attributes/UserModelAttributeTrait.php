<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Attributes;

use TheBachtiarz\Auth\Models\AbstractAuthUser;

/**
 * User Model Attribute Trait
 */
trait UserModelAttributeTrait
{
    /**
     * Define user model uses
     */
    protected AbstractAuthUser|null $userModel = null;

    // ? Getter Modules

    /**
     * Get user model
     */
    public function getUserModel(): AbstractAuthUser|null
    {
        return $this->userModel ?? tbauthconfig('user_model_override');
    }

    // ? Setter Modules

    /**
     * Set user model
     */
    public function setUserModel(AbstractAuthUser|null $userModel = null): static
    {
        $this->userModel = $userModel;

        return $this;
    }
}
