<?php

namespace App\Exceptions;

final class ServiceNotFoundException extends ConsoleException
{
    public function __construct(string $abstract)
    {
        parent::__construct("Service not found: $abstract");
    }
}
