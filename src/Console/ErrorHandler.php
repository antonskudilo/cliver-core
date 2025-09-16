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

        $description = $exitCode->description();
        $errorMessage = $e->getMessage();

        if (strlen($errorMessage)) {
            $description .= ": $errorMessage";
        }

        errorln($description);

        if (is_debug()) {
            errorln($e->getTraceAsString());
        }

        return $exitCode;
    }
}
