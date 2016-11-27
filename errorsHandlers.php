<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/* /catch only NotFoundHttpException */
$app->error(function (NotFoundHttpException $e) use ($app) {
    return new JsonResponse(["message" => $e->getMessage()], 404);
});

/* catch everything else */
$app->error(function (\Exception $e, $code) use ($app) {

    /* catch Exceptions */
    if (!empty($e->getMessage())) {
        return new JsonResponse(["message" => $e->getMessage()], 400);
    }

    /* handle HTTP codes */
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});
