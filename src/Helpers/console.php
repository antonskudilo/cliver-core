<?php

if (!function_exists('println')) {
    /**
     * Prints a message to STDOUT with a line break.
     *
     * @param string $message
     * @return void
     */
    function println(string $message = ''): void
    {
        fwrite(STDOUT, $message . PHP_EOL);
    }
}

if (!function_exists('errorln')) {
    /**
     * Prints a message to STDERR with a line break.
     *
     * @param string $message
     * @return void
     */
    function errorln(string $message = ''): void
    {
        fwrite(STDERR, "[Error] " . $message . PHP_EOL);
    }
}

if (!function_exists('pad')) {
    /**
     * Pads a label and appends a value for aligned console output.
     *
     * Example:
     *   println(pad("Total orders:", "150"));
     *
     * @param string $label
     * @param string $value
     * @param int $padLength
     * @return string
     */
    function pad(string $label, string $value, int $padLength = 25): string
    {
        return str_pad($label, $padLength) . $value;
    }
}

/**
 * Automatically formats an array of "key => value" strings,
 * aligning all keys to the maximum length
 *
 * @param array<string,string|int|float> $rows
 * @return void
 */
function padAuto(array $rows): void
{
    if (empty($rows)) {
        return;
    }

    $maxLen = max(array_map('strlen', array_keys($rows))) + 2;

    foreach ($rows as $label => $value) {
        println(pad($label, $value, $maxLen));
    }
}
