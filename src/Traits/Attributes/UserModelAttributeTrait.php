<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Traits\Attributes;

use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\AuthUser;

use function app;
use function mb_strlen;
use function tbauthconfig;

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
        if ($this->userModel) {
            return $this->userModel;
        }

        $getOverride = tbauthconfig('user_model_override');

        if (! ! @mb_strlen($getOverride ?? '')) {
            $this->userModel = app($getOverride);

            return $this->userModel;
        }

        return new AuthUser();
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
