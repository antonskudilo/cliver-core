<?php

namespace Cliver\Core\Console;

final readonly class AppConfig
{
    public const string KEY_DEFAULT_COMMAND = 'default_command';
    public const string PATH_USER_PROVIDERS = 'bootstrap/providers.php';

    /**
     * @param string $path
     * @return void
     */
    public static function loadEnv(string $path): void
    {
        $envFile = join_path($path, '.env');

        if (file_exists($envFile)) {
            loadEnv($envFile);
        }
    }
}
