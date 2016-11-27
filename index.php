<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/errorsHandlers.php';

$app['debug'] = $app['params']['app']['debug'];

/* register controllers as a service, by doing this we can inject dependencies */
$app['products.controller'] = function() use ($app) {
    return new \App\Controllers\ProductController($app, 'App\Entities\Product');
};

$app['categories.controller'] = function() use ($app) {
    return new \App\Controllers\Controller($app, 'App\Entities\Category');
};

$app['prices.controller'] = function() use ($app) {
    return new \App\Controllers\Controller($app, 'App\Entities\Price');
};

/* populating routes as defined in parameters.yml */
$app->mount('/api/v1/', function ($prefix) use ($app) {

    foreach ($app['params']['routes'] as $route) {
        $prefix->{$route['method']}($route['path'], $route['action']);
    }

});

$app->run();