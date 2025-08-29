<?php

namespace Cliver\Core\Providers;

use Cliver\Core\Console\CommandRegistry;
use Cliver\Core\Console\Commands\CommandInterface;
use Cliver\Core\Core\Container;
use Cliver\Core\Exceptions\CommandAlreadyRegisteredException;
use Cliver\Core\Exceptions\ConsoleException;
use Cliver\Core\Exceptions\InvalidCommandClassException;
use ReflectionException;

abstract class BaseCommandServiceProvider implements ServiceProviderInterface
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

        foreach ($this->commands() as $command) {
            if (!is_subclass_of($command, CommandInterface::class)) {
                throw new InvalidCommandClassException($command);
            }

            /** @var CommandInterface $command */
            $name = $command::getName();

            if ($registry->has($name)) {
                throw new CommandAlreadyRegisteredException($name);
            }

            $registry->add($name, $command);
        }
    }

    /**
     * @return array<string>
     */
    abstract protected function commands(): array;
}
