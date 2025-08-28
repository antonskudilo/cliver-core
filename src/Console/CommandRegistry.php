<?php

namespace App\Console;

use App\Exceptions\CommandAlreadyRegisteredException;
use App\Exceptions\CommandNotFoundException;

final class CommandRegistry
{
    /** @var array<string, CommandDefinition> */
    private array $commands = [];

    /**
     * @param string $name
     * @param string $class
     * @return void
     * @throws CommandAlreadyRegisteredException
     */
    public function add(string $name, string $class): void
    {
        if (isset($this->commands[$name])) {
            throw new CommandAlreadyRegisteredException($name);
        }

        $this->commands[$name] = new CommandDefinition($name, $class);
    }

    /**
     * @return array<string, CommandDefinition>
     */
    public function all(): array
    {
        return $this->commands;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    /**
     * @param string $name
     * @return string
     * @throws CommandNotFoundException
     */
    public function get(string $name): string
    {
        if (!isset($this->commands[$name])) {
            throw new CommandNotFoundException($name);
        }

        return $this->commands[$name]->class;
    }
}
