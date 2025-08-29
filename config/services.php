<?php

use Cliver\Core\Console\AppConfig;
use Cliver\Core\Console\Commands\HelpCommand;

return [
    AppConfig::KEY_SINGLETONS => [
        // ...
    ],
    AppConfig::KEY_BINDINGS => [
        // ...
    ],
    AppConfig::KEY_DEFAULT_COMMAND => HelpCommand::class,
];
