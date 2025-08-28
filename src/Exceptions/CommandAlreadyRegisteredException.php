<?php

namespace App\Exceptions;

final class CommandAlreadyRegisteredException extends ConsoleException
{
    public function __construct(string $name)
    {
        parent::__construct("Command $name is already registered");
    }
}
