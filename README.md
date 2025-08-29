# PHP CLIVER (Core)

A minimalistic **core** for building a PHP CLI framework.  
The goal of this project is to provide a foundation (dependency injection container, services, providers, command registry) on top of which you can build your own CLI applications and extend functionality.

## Core features
- Simple command registration via config
- Dependency Injection support
- Service providers and singletons
- Can be used as a library to build your own CLI framework

## Requirements
- PHP 8.3 or higher

## Version

Current release: **0.1.0**

You can require the framework core via Composer:

```bash
composer require antonskudilo/cliver-core:^0.1
```

## Installation

```bash
git clone https://github.com/antonskudilo/cliver-core.git
cd cliver-core
composer install
composer dump-autoload
```

## Quick start
```bash
php bin/cliver
```

*By default, when no command name is provided, the application runs the HelpCommand.
It displays all available commands in the format signature → description:*

```bash
Available commands:
help   Show the list of available commands
```

*The default command can be redefined in the config\services.php configuration (see item "Service configuration").*

## Running commands
All commands are defined in config/commands.php.

#### Register the command in config/commands.php:

```bash
return [
    App\Console\Commands\PrintCommand::class,
];
```

Each command defines its own static getName() method, which is used as the CLI signature.

#### Example of a custom command:

```bash
final class PrintCommand implements CommandInterface
{
    private PrinterInterface $printer;

    public function __construct(PrinterInterface $printer)
    {
        $this->printer = $printer;
    }
    
    public static function getName(): string
    {
        return 'print';
    }

    public static function getDescription(): string
    {
        return 'Example print command';
    }

    public function execute(array $arguments = []): void
    {
        $this->printer->print();
    }
}
```

## Dependency injection container

The framework core includes a simple DI container.
Services can be registered in config/services.php or provided dynamically in tests.

#### PrinterInterface:

```bash
interface PrinterInterface
{
    public function print(): void;
}
```

#### ConsolePrinter:

```bash
class ConsolePrinter implements PrinterInterface
{
    public function print(): void
    {
        println('Hello, I`m a ConsolePrinter');
    }
}
```

## Service configuration

#### All service bindings are defined in the config/services.php file. It contains three main sections:

- singletons — a list of classes or interfaces that should be resolved as singletons (same instance reused).
- bindings — regular service bindings, where each call to the container returns a new instance.
- default_command — the command that will run if no command name is provided (by default, HelpCommand).

This allows you to configure how dependencies are resolved across the application, and makes it easy to swap or mock implementations in tests.

#### Service binding (config/services.php):

```bash
return [
  'bindings' => [
    App\Services\PrinterInterface::class => App\Services\ConsolePrinter::class,
  ],
];
```

#### Now you can run:

```bash
php bin/cliver print
```

## Overriding dependencies in tests

When writing tests, you can replace services on-the-fly using the DI container.
This is useful for mocking dependencies like console output, external API calls, etc.

#### Example: overriding the PrinterInterface with a test implementation:

```bash
$this->container->bind(
    App\Services\PrinterInterface::class,
    new App\Tests\TestPrinter()
);
```
This allows your command to use the test printer instead of the real console printer.

#### TestPrinter:

```bash
class TestPrinter implements PrinterInterface
{
    public function print(): void
    {
        echo 'Hello, I`m a TestPrinter';
    }
}
```

## Base test case

A base test class (tests/TestCase.php) is provided to bootstrap the application and container for every test:

```bash
class TestCase extends BaseTestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = Bootstrap::init();
    }

    protected function makeApp(): Application
    {
        return $this->container->get(Application::class);
    }
    
    protected function fake(string $abstract, object $fake): object
    {
        $this->swap($abstract, $fake);

        return $fake;
    }
    
    protected function swap(string $abstract, object $fake): void
    {
        $this->container->bind($abstract, $fake);
    }
    
    protected function fakeSingleton(string $abstract, object $fake): object
    {
        $this->swapSingleton($abstract, $fake);

        return $fake;
    }
    
    protected function swapSingleton(string $abstract, object $fake): void
    {
        $this->container->singleton($abstract, $fake);
    }
}
```

## Testing a command

#### Example of a test for a command registered in config/commands.php:

```bash
final class PrintCommandTest extends TestCase
{
    public function testCommandOutput(): void
    {
        $this->fakeSingleton(
            PrinterInterface::class, 
            new TestPrinter()
        );

        ob_start();
        $app->run(['print']);
        $output = ob_get_clean();
        
        $this->assertStringContainsString(
            'Hello, I`m a TestPrinter', 
            $output
        );
    }
}
```

## Running tests

```bash
vendor/bin/phpunit --colors=always
```

## Helpers

This project includes a set of helper functions grouped into console, environment, and path utilities.
They simplify working with CLI output, environment variables, and project paths.

#### Console helpers

- **errorln(string $message = '')** – print a message to STDERR with a [Error] prefix.
- **pad(string $label, string $value, int $padLength = 25)** – format label/value pairs with aligned output.
- **padAuto(array $rows)** – automatically align and print an array of key => value pairs.
- **println(string $message = '')** – print a message to STDOUT with a newline.

#### Environment helpers

- **env(string $key, mixed $default = null)** – retrieve an environment variable with type casting (true/false/null).
- **is_debug()** – check if APP_DEBUG is enabled.
- **loadEnv(string $path)** – load variables from a .env file into $_ENV, $_SERVER, and getenv().

#### Path helpers

- **base_path(string $path = '')** – get the absolute path relative to the project root.
- **config_path(string $path = '')** – get the absolute path to the config/ directory.
- **join_path(string $base, string $path = '')** – safely concatenate directory paths.

## Environment configuration

The application uses a .env file in the project root to configure environment variables.
A template file .env.example is provided and can be copied to create your own .env.

#### Currently, it supports:

- **APP_DEBUG** — when set to true, full stack traces are displayed in the console.
Otherwise, only a short error message is shown. This allows easy switching between development and production modes.


## Project structure

```bash
├── bin/cliver                    # CLI entry script
├── config/                       # Configs (commands, providers, services)
├── src/                          # Core source code
│   ├── Console/                  # CLI commands, input/output handling
│   │   └── Commands/             # Core commands, CommandInterface
│   ├── Core/                     # Bootstrap, DI container
│   ├── Exceptions/               # Exceptions
│   ├── Helpers/                  # Helpers should be included in src/helpers.php
│   └── Providers/                # Core service providers
├── tests/                        # PHPUnit tests
├── composer.json
└── phpunit.xml
```

### License

MIT


