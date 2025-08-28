<?php

namespace App\Console;

use App\Console\Commands\CommandInterface;
use App\Core\Container;
use App\Exceptions\ConsoleException;
use ReflectionException;

final readonly class Application
{
    public function __construct(
        private CommandResolver $resolver,
        private Container $container
    ) {}

    /**
     * @param array $argv
     * @return ExitCode
     * @throws ConsoleException|ReflectionException
     */
    public function run(array $argv): ExitCode
    {
        $input = new Input($argv);
        $this->getCommand($input)
            ->execute($input->getArguments());

        return ExitCode::SUCCESS;
    }

    /**
     * @param Input $input
     * @return CommandInterface
     * @throws ConsoleException|ReflectionException
     */
    private function getCommand(Input $input): CommandInterface
    {
        $commandName = $input->getCommandName();

        if (isset($commandName)) {
            return $this->resolver->resolve($commandName);
        }

        return $this->getDefaultCommand();
    }

    /**
     * @return CommandInterface
     * @throws ConsoleException|ReflectionException
     */
    private function getDefaultCommand(): CommandInterface
    {
        return $this->container->get(AppConfig::KEY_DEFAULT_COMMAND);
    }
}
