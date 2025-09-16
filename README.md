# PHP CLIVER (Core)

A minimalistic **core** for building a PHP CLI framework.  
The goal of this project is to provide a foundation (dependency injection container, services, providers, command registry) on top of which you can build your own CLI applications and extend functionality.

## Core features
- Simple command registration
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

*By default, when no command name is provided, the application runs the `HelpCommand`.
It displays all available commands in the format signature → description:*

```bash
Available commands:
help   Show the list of available commands
```

*The default command can be redefined in the `Providers/AppServiceProvider` (see item "Service configuration").*

## Running commands
To register and use commands, they must be added to `Providers/CommandServiceProvider`.  
Core commands (for example, `HelpCommand`) should be registered in `Console/Commands/CoreCommands.php`.

#### Register the command in `Console/Commands/CoreCommands.php`:

```bash
    public static function commands(): array
    {
        return [
            HelpCommand::class,
        ];
    }
```

Each command defines its own static method `getName`, which is used as the CLI signature.

#### Help command:

```bash
final readonly class HelpCommand implements CommandInterface
{
    public static function getName(): string
    {
        return 'help';
    }
    
    ...
}
```

## Dependency injection container

The framework core includes a simple DI container.
Core services should be registered in `Providers/CoreProviders`.
Services can be registered in `Providers/AppServiceProvider` or provided dynamically.

## Service configuration

#### Service bindings could be defined in the `Providers/AppServiceProvider`. 
It contains the `register` method, which can be used for `bind` and `singleton`, using the container instance passed as a parameter.

This allows you to configure how dependencies are resolved across the application, and makes it easy to swap or mock implementations in tests.

#### `Providers/AppServiceProvider`:

```bash
final class AppServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(
          AppConfig::KEY_DEFAULT_COMMAND, 
          HelpCommand::class
        );
    }
}
```

#### Now you can run:

```bash
php bin/cliver help
```

## Base test case

A base test class `Testing/TestCase.php` is provided to bootstrap the application and container for every test:

```bash
abstract class TestCase extends BaseTestCase
{
    protected Container $container;

    protected function getContainer(string $basePath): void
    {
        $this->container = Bootstrap::init($basePath);
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

## Helpers

This project includes a set of helper functions grouped into console, environment, and path utilities.
They simplify working with CLI output, environment variables, and project paths.

#### Console helpers

- `errorln(string $message = '')` – print a message to STDERR with a [Error] prefix.
- `pad(string $label, string $value, int $padLength = 25)` – format label/value pairs with aligned output.
- `padAuto(array $rows)` – automatically align and print an array of key => value pairs.
- `println(string $message = '')` – print a message to STDOUT with a newline.

#### Environment helpers

- `env(string $key, mixed $default = null)` – retrieve an environment variable with type casting (true/false/null).
- `is_debug()` – check if APP_DEBUG is enabled.
- `loadEnv(string $path)` – load variables from a .env file into $_ENV, $_SERVER, and getenv().

#### Path helpers

- `load_from(string $path, mixed $default = [])` – loading data from a specific file
- `join_path(string $base, string $path = '')` – safely concatenate directory paths.

## Environment configuration

The application uses a `.env` file in the project root to configure environment variables.
A template file `.env.example` is provided and can be copied to create your own `.env`.

#### Currently, it supports:

- `APP_DEBUG` — when set to true, full stack traces are displayed in the console.
Otherwise, only a short error message is shown. This allows easy switching between development and production modes.


## Project structure

```bash
├── src/                          # Core source code
│   ├── Console/                  # CLI commands, input/output handling
│   │   └── Commands/             # Core commands, CommandInterface
│   ├── Core/                     # Bootstrap, DI container
│   ├── Exceptions/               # Exceptions
│   ├── Helpers/                  # Helpers should be included in src/helpers.php
│   ├── Providers/                # Core service providers
│   └── Testing/                  # Testing utilities
├── tests/                        # PHPUnit tests
├── cliver                        # CLI entry script
├── composer.json
└── phpunit.xml
```

### License

MIT


