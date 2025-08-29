<?php

namespace Cliver\Core\Providers;

class CoreProviders
{
    /**
     * @return array
     */
    public static function providers(): array
    {
        return [
            AppServiceProvider::class,
            CommandServiceProvider::class,
        ];
    }
}
