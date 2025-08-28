<?php

namespace App\Console;

final readonly class AppConfig
{
    public const string KEY_BINDINGS = 'bindings';
    public const string KEY_DEFAULT_COMMAND = 'default_command';
    public const string KEY_SINGLETONS = 'singletons';

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
    public static function pathCoreProviders(): string
    {
        return __DIR__ . '/config/providers.php';
    }

    /**
     * @return string
     */
    public static function pathUserProviders(): string
    {
        return base_path('config/providers.php');
    }

    /**
     * @return string
     */
    public static function pathCoreServices(): string
    {
        return __DIR__ . '/config/services.php';
    }

    /**
     * @return string
     */
    public static function pathUserServices(): string
    {
        return base_path('config/services.php');
    }

    /**
     * @return string
     */
    public static function pathCoreCommands(): string
    {
        return __DIR__ . '/config/commands.php';
    }

    /**
     * @return string
     */
    public static function pathUserCommands(): string
    {
        return base_path('config/commands.php');
    }
}
