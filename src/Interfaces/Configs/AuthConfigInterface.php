<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Interfaces\Configs;

interface AuthConfigInterface
{
    /**
     * Config name
     */
    public const CONFIG_NAME = 'thebachtiarz_auth';

    /**
     * Identity method
     */
    public const IDENTITY_METHOD = 'user_auth_identity_method';
}
