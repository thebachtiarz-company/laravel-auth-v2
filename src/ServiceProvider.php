<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use TheBachtiarz\Auth\Interfaces\Config\AuthConfigInterface;
use TheBachtiarz\Auth\Providers\AppsProvider;

use function app;
use function assert;
use function config_path;
use function database_path;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $appsProvider = $this->app->make(AppsProvider::class);
        assert($appsProvider instanceof AppsProvider);

        $appsProvider->registerConfig();

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(AppsProvider::COMMANDS);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! app()->runningInConsole()) {
            return;
        }

        $_configName  = AuthConfigInterface::CONFIG_NAME;
        $_publishName = 'thebachtiarz-auth';

        $this->publishes([__DIR__ . "/../config/$_configName.php" => config_path("$_configName.php")], "$_publishName-config");
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], "$_publishName-migrations");
    }
}
