<?php

namespace Cliver\Core\Providers;

use Cliver\Core\Console\AppConfig;
use Cliver\Core\Console\Commands\HelpCommand;
use Cliver\Core\Core\Container;

final class AppServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $bindings = [
            AppConfig::KEY_DEFAULT_COMMAND => HelpCommand::class,
        ];

        foreach ($bindings as $abstract => $concrete) {
            $container->bind($abstract, $concrete);
        }
    }
}
