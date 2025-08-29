<?php

namespace Cliver\Core\Exceptions;

use Cliver\Core\Console\ExitCode;

class CommandNotFoundException extends ConsoleException
{
    public function __construct(string $command)
    {
        parent::__construct("Unknown command: $command", ExitCode::NOT_FOUND);
    }
}
