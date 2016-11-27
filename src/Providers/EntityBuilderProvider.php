<?php

namespace App\Providers;

use \Pimple\Container;
use \App\Builders\EntityBuilder;
use \Pimple\ServiceProviderInterface;

class EntityBuilderProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['entity_builder'] = function ($app) {
            return new EntityBuilder($app);
        };
    }
}