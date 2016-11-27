<?php

namespace App\Providers;

use \Pimple\Container;
use App\Builders\ResponseBuilder;
use \Pimple\ServiceProviderInterface;

class ResponseBuilderProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['response_builder'] = function ($app) {
            return new ResponseBuilder($app);
        };
    }
}