<?php

namespace Cliver\Core\Console\Commands;

class CoreCommands
{
    /**
     * @return array<string>
     */
    public static function commands(): array
    {
        return [
            HelpCommand::class,
        ];
    }
}
