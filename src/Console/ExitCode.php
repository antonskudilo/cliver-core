<?php

namespace App\Console;

enum ExitCode: int
{
    case SUCCESS = 0;
    case ERROR = 1;
    case NOT_FOUND = 2;
    case INVALID_ARGUMENT = 3;

    /**
     * Get description for the exit code.
     *
     * @return string
     */
    public function description(): string
    {
        return match ($this) {
            self::SUCCESS => 'Command executed successfully',
            self::ERROR => 'General error occurred',
            self::NOT_FOUND => 'Command not found',
            self::INVALID_ARGUMENT => 'Invalid argument provided',
        };
    }
}
