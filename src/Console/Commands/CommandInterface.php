<?php

namespace Cliver\Core\Console\Commands;

interface CommandInterface
{
    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return string
     */
    public static function getDescription(): string;

    /**
     * @param array $args
     * @return void
     */
    public function execute(array $args): void;
}
