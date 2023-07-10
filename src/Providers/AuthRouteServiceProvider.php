<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use TheBachtiarz\Base\AppConfigInterface;

use function tbbaseconfig;

class AuthRouteServiceProvider extends RouteServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        if (! tbauthconfig(keyName: 'route_service', useOrigin: false)) {
            return;
        }

        $this->routes(static function (): void {
            Route::prefix(tbbaseconfig(
                keyName: AppConfigInterface::CONFIG_APP_PREFIX,
                useOrigin: false,
            ))->group(tbauthrestapipath());
        });
    }
}
