<?php

if (!function_exists('join_path')) {
    /**
     * Safely join base path with optional sub path.
     *
     * @param string $base
     * @param string $path
     * @return string
     */
    function join_path(string $base, string $path = ''): string
    {
        return rtrim($base, '/') . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('load_from')) {
    /**
     * @param string $path
     * @param mixed $default
     * @return array
     */
    function load_from(string $path, mixed $default = []): array
    {
        if (file_exists($path)) {
            require $path;
        }

        return $default;
    }
}