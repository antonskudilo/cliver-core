<?php
namespace Cliver\Core\Core;

use Cliver\Core\Console\AppConfig;
use Cliver\Core\Console\CommandRegistry;
use Cliver\Core\Console\CommandResolver;
use Cliver\Core\Providers\CoreProviders;
use Cliver\Core\Providers\ServiceProviderInterface;

final readonly class Bootstrap
{
    /**
     * @param string $basePath
     * @return Container
     */
    public static function init(string $basePath): Container
    {
        AppConfig::loadEnv($basePath);

        $container = new Container();

        self::registerCore($container);
        self::registerProviders($container, $basePath);

        return $container;
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
     * @param string $basePath
     * @return void
     */
    private static function registerProviders(Container $container, string $basePath): void
    {
         foreach (self::loadProviders($basePath) as $providerClass) {
            $provider = new $providerClass();
            $provider->register($container);
        }
    }

    /**
     * @return array<ServiceProviderInterface>
     */
    private static function loadProviders(string $basePath): array
    {
        return array_merge(
            CoreProviders::providers(),
            load_from(join_path($basePath, AppConfig::PATH_USER_PROVIDERS))
        );
    }
}
