<?php

if (!function_exists('config_path')) {
    /**
     * @param string $path
     * @return string
     */
    function config_path(string $path = ''): string
    {
        return join_path(base_path('config'), $path);
    }
}

if (!function_exists('base_path')) {
    /**
     * @param string $path
     * @return string
     */
    function base_path(string $path = ''): string
    {
        $root = realpath(__DIR__ . '/../../');

        return join_path($root, $path);
    }
}

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
