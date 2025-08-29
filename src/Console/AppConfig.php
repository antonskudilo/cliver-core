<?php

namespace Cliver\Core\Console;

final readonly class AppConfig
{
    public const string KEY_DEFAULT_COMMAND = 'default_command';

    /**
     * @param string $path
     * @return array
     */
    public static function loadConfig(string $path): array
    {
        return file_exists($path) ? require $path : [];
    }

    /**
     * @return string
     */
    public static function pathUserProviders(): string
    {
        return base_path('bootstrap/providers.php');
    }
}
