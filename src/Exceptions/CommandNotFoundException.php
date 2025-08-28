<?php

namespace App\Exceptions;

use App\Console\ExitCode;

class CommandNotFoundException extends ConsoleException
{
    public function __construct(string $command)
    {
        parent::__construct("Unknown command: $command", ExitCode::NOT_FOUND);
    }
}
