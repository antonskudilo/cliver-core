<?php
namespace App\Core;

use App\Console\AppConfig;
use App\Console\CommandRegistry;
use App\Console\CommandResolver;
use App\Providers\ServiceProviderInterface;

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
        self::registerServices($container);

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
        return AppConfig::loadConfig(AppConfig::pathCoreProviders());
    }

    /**
     * @return array<ServiceProviderInterface>
     */
    private static function loadUserProviders(): array
    {
        return AppConfig::loadConfig(AppConfig::pathUserProviders());
    }

    /**
     * @param Container $container
     * @return void
     */
    private static function registerServices(Container $container): void
    {
        $services = self::getServices();

        foreach ($services[AppConfig::KEY_SINGLETONS] as $abstract => $concrete) {
            $container->singleton($abstract, $concrete);
        }

        foreach ($services[AppConfig::KEY_BINDINGS] as $abstract => $concrete) {
            $container->bind($abstract, $concrete);
        }

        if (isset($services[AppConfig::KEY_DEFAULT_COMMAND])) {
            $container->bind(AppConfig::KEY_DEFAULT_COMMAND, $services[AppConfig::KEY_DEFAULT_COMMAND]);
        }
    }

    /**
     * @return array
     */
    private static function getServices(): array
    {
        $coreServices = self::loadCoreServices();
        $userServices = self::loadUserServices();

        return [
            AppConfig::KEY_SINGLETONS => array_merge(
                $coreServices[AppConfig::KEY_SINGLETONS] ?? [],
                $userServices[AppConfig::KEY_SINGLETONS] ?? []
            ),
            AppConfig::KEY_BINDINGS => array_merge(
                $coreServices[AppConfig::KEY_BINDINGS] ?? [],
                $userServices[AppConfig::KEY_BINDINGS] ?? []
            ),
            AppConfig::KEY_DEFAULT_COMMAND => $userServices[AppConfig::KEY_DEFAULT_COMMAND]
                ?? $coreServices[AppConfig::KEY_DEFAULT_COMMAND]
                    ?? null,
        ];
    }

    /**
     * @return array
     */
    private static function loadCoreServices(): array
    {
        return AppConfig::loadConfig(AppConfig::pathCoreServices());
    }

    /**
     * @return array
     */
    private static function loadUserServices(): array
    {
        return AppConfig::loadConfig(AppConfig::pathUserServices());
    }
}
