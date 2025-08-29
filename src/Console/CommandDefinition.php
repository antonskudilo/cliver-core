<?php

namespace Cliver\Core\Console;

use Cliver\Core\Console\Commands\CommandInterface;

final readonly class CommandDefinition
{
    /**
     * @param string $name
     * @param class-string<CommandInterface> $class
     */
    public function __construct(
        public string $name,
        public string $class,
    ) {}
}
