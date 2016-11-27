<?php

namespace App\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function __construct(Application $app, string $entity_name)
    {
        parent::__construct($app, $entity_name);
    }

    /**
     * This is here for demo only, i populate multiple related entities at once. This is not a real world use but just to show it works
     *
     * @param Request $request
     *
     * @return string
     */
    public function post(Request $request)
    {
        $fields = json_decode($request->getContent(), true);

        /* optional for demo, this will build related entities on the fly.
         * They are not validated here in order to keep this example short */
        $fields['price'] = $this->eb->build('App\Entities\Price', $fields['price']);
        $fields['categories'] = $this->eb->buildMultiple('App\Entities\Category', $fields['categories']);

        /* building the entity */
        $entity = $this->eb->build($this->getEntityName(), $fields);

        /* validating the entity */
        $errors = $this->validator->validate($entity);

        /* if we have validation errors we return them */
        if(count($errors)) {
            return $this->rb->buildJsonResponse($errors, 422);
        }

        /* well done, we flush */
        $this->em->persist($entity);
        $this->em->flush($entity);

        return $this->rb->buildJsonResponse($entity);
    }

}