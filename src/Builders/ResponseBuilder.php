<?php

namespace App\Builders;

use Pimple\Container;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseBuilder
{
    private $app;

    /**
     * ApiResponse constructor.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param $entity
     * @param int $http_code
     *
     * @return JsonResponse
     */
    public function buildJsonResponse($entity, $http_code = 200): JsonResponse
    {
        /**
         * @var $serializer Serializer
         */
        $serializer = $this->app['serializer'];

        $object = json_decode($serializer->serialize($entity, 'json'));

        return new JsonResponse($object, $http_code);
    }

}