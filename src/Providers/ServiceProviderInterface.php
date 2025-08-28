<?php

namespace App\Providers;

use App\Core\Container;

interface ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void;
}
