<?php

namespace Cliver\Core\Console;

use Cliver\Core\Exceptions\ConsoleException;
use Throwable;

final readonly class ErrorHandler
{
    /**
     * Handles exception and prints error description
     *
     * @param Throwable $e
     * @return ExitCode
     */
    public static function handle(Throwable $e): ExitCode
    {
        if ($e instanceof ConsoleException) {
            $exitCode = $e->getExitCode();
        } else {
            $exitCode = ExitCode::ERROR;
        }

        errorln($exitCode->description());

        if (is_debug()) {
            errorln(PHP_EOL . $e->getMessage() . PHP_EOL);
            errorln(PHP_EOL . $e->getTraceAsString() . PHP_EOL);
        }

        return $exitCode;
    }
}
