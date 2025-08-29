<?php

namespace Cliver\Core\Core;

use Cliver\Core\Exceptions\CannotResolveParameterException;
use Cliver\Core\Exceptions\ConsoleException;
use Cliver\Core\Exceptions\InvalidBindingException;
use Cliver\Core\Exceptions\ServiceNotFoundException;
use ReflectionClass;
use ReflectionException;

final class Container
{
    /** @var array<class-string, callable|class-string|object> */
    private array $bindings = [];

    /** @var array<class-string, object> */
    private array $singletons = [];

    /**
     * @param string $abstract
     * @param string|callable|object $concrete
     * @return void
     */
    public function bind(string $abstract, string|callable|object $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * @param string $abstract
     * @param string|callable|object $concrete
     * @return void
     */
    public function singleton(string $abstract, string|callable|object $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
        $this->singletons[$abstract] = null;
    }

    /**
     * @param string $abstract
     * @return object
     * @throws ConsoleException|ReflectionException
     */
    public function get(string $abstract): object
    {
        if ($this->hasSingleton($abstract)) {
            return $this->getSingleton($abstract);
        }

        if ($this->hasBinding($abstract)) {
            $object = $this->getBinding($abstract);

            if ($this->hasSingletonKey($abstract)) {
                /* // Store in singletons if needed */
                $this->addSingleton($abstract, $object);
            }

            return $object;
        }

        if (class_exists($abstract)) {
            /* Store in singletons if applicable */
            return $this->build($abstract);
        }

        throw new ServiceNotFoundException($abstract);
    }

    /**
     * Checks if a singleton exists and is already instantiated
     *
     * @param string $abstract
     * @return bool
     */
    private function hasSingleton(string $abstract): bool
    {
        return array_key_exists($abstract, $this->singletons)
            && $this->singletons[$abstract] !== null;
    }

    /**
     * @param string $abstract
     * @return object
     */
    private function getSingleton(string $abstract): object
    {
        return $this->singletons[$abstract];
    }

    /**
     * Checks if a binding exists for the given abstract
     *
     * @param string $abstract
     * @return bool
     */
    private function hasBinding(string $abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }

    /**
     * @param string $abstract
     * @return object
     * @throws ConsoleException|ReflectionException
     */
    private function getBinding(string $abstract): object
    {
        if ($this->hasBinding($abstract)) {
            $concrete = $this->bindings[$abstract];

            return $this->resolveBinding($abstract, $concrete);
        }

        throw new InvalidBindingException($abstract);
    }

    /**
     * @param string $abstract
     * @return bool
     */
    private function hasSingletonKey(string $abstract): bool
    {
        return array_key_exists($abstract, $this->singletons);
    }

    /**
     * @param string $abstract
     * @param object $object
     * @return void
     */
    private function addSingleton(string $abstract, object $object): void
    {
        $this->singletons[$abstract] = $object;
    }

    /**
     * Resolves a binding to an object instance
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return object
     * @throws ConsoleException|ReflectionException
     */
    private function resolveBinding(string $abstract, mixed $concrete): object
    {
        return match (true) {
            /* If binding is a class name, build it */
            is_string($concrete) && class_exists($concrete) => $this->build($concrete),

            /* If binding is a callable, call it with the container */
            is_callable($concrete) => $concrete($this),

            /* If binding is already an object, return it */
            is_object($concrete) => $concrete,

            /* Invalid binding */
            default => throw new InvalidBindingException($abstract),
        };
    }

    /**
     * @throws ConsoleException|ReflectionException
     */
    private function build(string $class): object
    {
        $reflectionClass = new ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $params = [];

        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if ($type && !$type->isBuiltin()) {
                $params[] = $this->get($type->getName());
            } elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                throw new CannotResolveParameterException($class, $param->getName());
            }
        }

        return $reflectionClass->newInstanceArgs($params);
    }
}
