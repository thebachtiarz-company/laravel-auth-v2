<?php

declare(strict_types=1);

namespace TheBachtiarz\Auth\Providers;

use function app;
use function assert;
use function config;

class AppsProvider
{
    /**
     * Library Commands
     */
    public const COMMANDS = [];

    /**
     * Register config
     */
    public function registerConfig(): void
    {
        $_dataProvider = app(DataProvider::class);
        assert($_dataProvider instanceof DataProvider);

        foreach ($_dataProvider->registerConfig() as $key => $register) {
            config($register);
        }
    }
}
