<?php

namespace App\Providers;

use App\Console\AppConfig;
use App\Console\CommandRegistry;
use App\Console\Commands\CommandInterface;
use App\Core\Container;
use App\Exceptions\CommandAlreadyRegisteredException;
use App\Exceptions\ConsoleException;
use App\Exceptions\InvalidCommandClassException;
use ReflectionException;

final class CommandServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     * @throws ConsoleException|ReflectionException
     */
    public function register(Container $container): void
    {
        /** @var CommandRegistry $registry */
        $registry = $container->get(CommandRegistry::class);
        $commands = self::getCommands();

        foreach ($commands as $class) {
            if (!is_subclass_of($class, CommandInterface::class)) {
                throw new InvalidCommandClassException($class);
            }

            /** @var CommandInterface $class */
            $name = $class::getName();

            if ($registry->has($name)) {
                throw new CommandAlreadyRegisteredException($name);
            }

            $registry->add($name, $class);
        }
    }

    /**
     * @return array<string>
     */
    private static function getCommands(): array
    {
        return array_merge(
            self::loadCoreCommands(),
            self::loadUserCommands()
        );
    }

    /**
     * @return array<string>
     */
    private static function loadCoreCommands(): array
    {
        return AppConfig::loadConfig(AppConfig::pathCoreCommands());
    }

    /**
     * @return array<string>
     */
    private static function loadUserCommands(): array
    {
        return AppConfig::loadConfig(AppConfig::pathUserCommands());
    }
}
