<?php

namespace Cliver\Core\Console\Commands;

use Cliver\Core\Console\CommandRegistry;
use Cliver\Core\Core\Container;
use Cliver\Core\Exceptions\ConsoleException;
use ReflectionException;

final readonly class HelpCommand implements CommandInterface
{
    /**
     * @param CommandRegistry $registry
     * @param Container $container
     */
    public function __construct(
        private CommandRegistry $registry,
        private Container $container,
    ) {}

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'help';
    }

    /**
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Show the list of available commands';
    }

    /**
     * @param array $args
     * @return void
     * @throws ConsoleException|ReflectionException
     */
    public function execute(array $args): void
    {
        $rows = [];

        foreach ($this->registry->all() as $definition) {
            $command = $this->container->get($definition->class);
            $rows[$command->getName()] = $command->getDescription();
        }

        println("Available commands:");
        padAuto($rows);
    }
}
