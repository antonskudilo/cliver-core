<?php

namespace App\Console;

final class Input
{
    /**
     * @var null|string
     */
    private null|string $command;

    /** @var string[] */
    private array $args;

    public function __construct(array $argv)
    {
        if (
            !empty($argv[0])
            && (
                str_contains($argv[0], '/')
                || str_ends_with($argv[0], '.php')
                || file_exists($argv[0])
            )
        ) {
            array_shift($argv);
        }

        $this->command = $argv[0] ?? null;
        $this->args = array_slice($argv, 1);
    }

    public function getCommandName(): ?string
    {
        return $this->command;
    }

    /** @return string[] */
    public function getArguments(): array
    {
        return $this->args;
    }
}
