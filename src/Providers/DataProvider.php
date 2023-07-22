<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Providers;

use TheBachtiarz\Auth\Interfaces\Configs\AuthConfigInterface;
use TheBachtiarz\Auth\Models\AuthUser;
use TheBachtiarz\Base\BaseConfigInterface;

use function array_merge;
use function config;
use function tbbaseconfig;

class DataProvider
{
    /**
     * List of config who need to registered into current project.
     * Perform by auth library.
     *
     * @return array
     */
    public function registerConfig(): array
    {
        $registerConfig = [];

        // ! Auth
        $registerConfig[] = ['auth.providers.users.model' => AuthUser::class];

        // ! Providers
        $_providers       = config('app.providers');
        $registerConfig[] = [
            'app.providers' => array_merge(
                $_providers,
                [AuthRouteServiceProvider::class],
            ),
        ];

        // ! library configs
        $configRegistered = tbbaseconfig(BaseConfigInterface::CONFIG_REGISTERED);
        $registerConfig[] = [
            BaseConfigInterface::CONFIG_NAME . '.' . BaseConfigInterface::CONFIG_REGISTERED => array_merge(
                $configRegistered,
                [AuthConfigInterface::CONFIG_NAME],
            ),
        ];

        return $registerConfig;
    }
}
