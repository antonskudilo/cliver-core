<?php

namespace Cliver\Core\Providers;

use Cliver\Core\Console\Commands\CoreCommands;

class CommandServiceProvider extends BaseCommandServiceProvider
{
    /**
     * @return array<string>
     */
    protected function commands(): array
    {
        return CoreCommands::commands();
    }
}
