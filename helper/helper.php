<?php

declare(strict_types=1);

use TheBachtiarz\Auth\Interfaces\Config\AuthConfigInterface;
use TheBachtiarz\Auth\Interfaces\Model\AuthUserInterface;
use TheBachtiarz\Auth\Models\AbstractAuthUser;
use TheBachtiarz\Auth\Models\AuthUser;

if (! function_exists('tbauthconfig')) {
    /**
     * TheBachtiarz auth config
     *
     * @param string|null $keyName   Config key name | null will return all
     * @param bool|null   $useOrigin Use original value from config
     */
    function tbauthconfig(string|null $keyName = null, bool|null $useOrigin = true): mixed
    {
        $configName = AuthConfigInterface::CONFIG_NAME;

        return tbconfig($configName, $keyName, $useOrigin);
    }
}

if (! function_exists('authuser')) {
    /**
     * Interact with user auth
     *
     * @param AbstractAuthUser|null $abstractAuthUser Set for overwrite auth.
     */
    function authuser(AbstractAuthUser|null $abstractAuthUser = null): AbstractAuthUser|null
    {
        $authClass = AuthUser::class;

        if ($abstractAuthUser) {
            if ($abstractAuthUser->getId()) {
                auth()->setUser($abstractAuthUser);
            }

            $authClass = $abstractAuthUser::class;
        }

        $authUser = app($authClass);
        assert($authUser instanceof AbstractAuthUser);

        return $authUser::find(auth()->id());
    }
}

if (! function_exists('authidentifiermethod')) {
    /**
     * Get auth identifier method
     */
    function authidentifiermethod(): string
    {
        return match (tbauthconfig(keyName: AuthConfigInterface::IDENTITY_METHOD, useOrigin: false)) {
            AuthUserInterface::ATTRIBUTE_USERNAME => AuthUserInterface::ATTRIBUTE_USERNAME,
            AuthUserInterface::ATTRIBUTE_EMAIL => AuthUserInterface::ATTRIBUTE_EMAIL,
            default => AuthUserInterface::ATTRIBUTE_EMAIL,
        };
    }
}

if (! function_exists('tbauthrestapipath')) {
    /**
     * Auth rest api path
     */
    function tbauthrestapipath(): string
    {
        return base_path('vendor/thebachtiarz-company/laravel-auth-v2/src/routes/auth_rest.php');
    }
}
