<?php

namespace Cliver\Core\Exceptions;

final class InvalidBindingException extends ConsoleException
{
    public function __construct(string $abstract)
    {
        parent::__construct("Invalid binding for service: $abstract");
    }
}
