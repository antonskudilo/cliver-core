<?php

namespace Cliver\Core\Exceptions;

final class CannotResolveParameterException extends ConsoleException
{
    public function __construct(string $class, string $paramName)
    {
        parent::__construct("Cannot resolve parameter $paramName for class $class");
    }
}
