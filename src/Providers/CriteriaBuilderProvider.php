<?php

namespace App\Providers;

use \Pimple\Container;
use App\Builders\CriteriaBuilder;
use \Pimple\ServiceProviderInterface;

class CriteriaBuilderProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['criteria_builder'] = function ($app) {
            return new CriteriaBuilder($app);
        };
    }
}