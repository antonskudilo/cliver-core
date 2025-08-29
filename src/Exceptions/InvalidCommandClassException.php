<?php

namespace Cliver\Core\Exceptions;

final class InvalidCommandClassException extends ConsoleException
{
    public function __construct(string $class)
    {
        parent::__construct("Command class $class must implement CommandInterface");
    }
}
