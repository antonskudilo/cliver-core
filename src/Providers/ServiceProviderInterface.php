<?php

namespace Cliver\Core\Providers;

use Cliver\Core\Core\Container;

interface ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void;
}
