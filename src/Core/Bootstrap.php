<?php
namespace Cliver\Core\Core;

use Cliver\Core\Console\AppConfig;
use Cliver\Core\Console\CommandRegistry;
use Cliver\Core\Console\CommandResolver;
use Cliver\Core\Providers\CoreProviders;
use Cliver\Core\Providers\ServiceProviderInterface;

final class Bootstrap
{
    /**
     * @return Container
     */
    public static function init(): Container
    {
        self::loadEnv();

        $container = new Container();

        self::registerCore($container);
        self::registerProviders($container);

        return $container;
    }

    /**
     * @return void
     */
    private static function loadEnv(): void
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            loadEnv($envFile);
        }
    }

    /**
     * @param Container $container
     * @return void
     */
    private static function registerCore(Container $container): void
    {
        $container->singleton(Container::class, $container);
        $container->singleton(CommandRegistry::class, new CommandRegistry());
        $container->singleton(CommandResolver::class, fn(Container $c) =>
            new CommandResolver($c->get(CommandRegistry::class), $c)
        );
    }

    /**
     * @param Container $container
     * @return void
     */
    private static function registerProviders(Container $container): void
    {
        foreach (self::getProviders() as $providerClass) {
            $provider = new $providerClass();
            $provider->register($container);
        }
    }

    /**
     * @return array<ServiceProviderInterface>
     */
    private static function getProviders(): array
    {
        return array_merge(
            self::loadCoreProviders(),
            self::loadUserProviders()
        );
    }

    /**
     * @return array<ServiceProviderInterface>
     */
    private static function loadCoreProviders(): array
    {
        return CoreProviders::providers();
    }

    /**
     * @return array<ServiceProviderInterface>
     */
    private static function loadUserProviders(): array
    {
        return AppConfig::loadConfig(AppConfig::pathUserProviders());
    }
}
