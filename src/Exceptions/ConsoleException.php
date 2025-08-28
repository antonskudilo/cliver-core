<?php

namespace App\Exceptions;

use App\Console\ExitCode;
use Exception;

class ConsoleException extends Exception
{
    /**
     * @param string $message
     * @param ExitCode $code
     */
    public function __construct(string $message, ExitCode $code = ExitCode::ERROR)
    {
        parent::__construct($message, $code->value);
    }

    /**
     * @return ExitCode
     */
    public function getExitCode(): ExitCode
    {
        return ExitCode::tryFrom($this->getCode()) ?? ExitCode::ERROR;
    }
}
