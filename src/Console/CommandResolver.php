<?php

namespace App\Console;

use App\Console\Commands\CommandInterface;
use App\Core\Container;
use App\Exceptions\ConsoleException;
use ReflectionException;

final readonly class CommandResolver
{
    /**
     * @param CommandRegistry $registry
     * @param Container $container
     */
    public function __construct(
        private CommandRegistry $registry,
        private Container $container
    ) {}

    /**
     * Finds and returns the command instance
     *
     * @throws ConsoleException|ReflectionException
     */
    public function resolve(string $name): CommandInterface
    {
        return $this->container->get(
            $this->registry->get($name)
        );
    }
}
