<?php

use App\Console\AppConfig;
use App\Console\Commands\HelpCommand;

return [
    AppConfig::KEY_SINGLETONS => [
        // ...
    ],
    AppConfig::KEY_BINDINGS => [
        // ...
    ],
    AppConfig::KEY_DEFAULT_COMMAND => HelpCommand::class,
];
