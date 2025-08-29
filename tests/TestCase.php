<?php

namespace Cliver\Core\Tests;

use Cliver\Core\Console\Application;
use Cliver\Core\Core\Bootstrap;
use Cliver\Core\Core\Container;
use Cliver\Core\Exceptions\ConsoleException;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionException;

class TestCase extends BaseTestCase
{
    protected Container $container;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = Bootstrap::init();
    }

    /**
     * @return Application
     * @throws ConsoleException|ReflectionException
     */
    protected function makeApp(): Application
    {
        return $this->container->get(Application::class);
    }

    /**
     * @param string $abstract
     * @param object $fake
     * @return object
     */
    protected function fake(string $abstract, object $fake): object
    {
        $this->swap($abstract, $fake);

        return $fake;
    }

    /**
     * @param string $abstract
     * @param object $fake
     * @return void
     */
    protected function swap(string $abstract, object $fake): void
    {
        $this->container->bind($abstract, $fake);
    }

    /**
     * @param string $abstract
     * @param object $fake
     * @return object
     */
    protected function fakeSingleton(string $abstract, object $fake): object
    {
        $this->swapSingleton($abstract, $fake);

        return $fake;
    }

    /**
     * @param string $abstract
     * @param object $fake
     * @return void
     */
    protected function swapSingleton(string $abstract, object $fake): void
    {
        $this->container->singleton($abstract, $fake);
    }
}
