<?php

namespace App\Controllers;

use Silex\Application;
use App\Traits\PopulateTrait;
use Doctrine\ORM\EntityManager;
use App\Builders\EntityBuilder;
use App\Builders\ResponseBuilder;
use App\Builders\CriteriaBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class Controller
{
    /**
     * @var $em Application
     */
    protected $app;

    /**
     * @var $em EntityManager
     */
    protected $em;

    /**
     * @var $eb EntityBuilder
     */
    protected $eb;

    /**
     * @var $cb CriteriaBuilder
     */
    protected $cb;

    /**
     * @var $cb ResponseBuilder
     */
    protected $rb;

    protected $validator;

    /**
     * @var $entity_name string
     */
    protected $entity_name;

    /**
     * Controller constructor.
     *
     * @param Application $app
     * @param string $entity_name
     */
    public function __construct(Application $app, string $entity_name)
    {
        /**
         * @var $eb Application
         */
        $this->app = $app;

        /**
         * @var $entity_name string
         */
        $this->entity_name = $entity_name;

        /**
         * @var $eb EntityBuilder
         */
        $this->eb = $this->app['entity_builder'];

        /**
         * @var $em EntityManager
         */
        $this->em = $this->app['orm.em'];

        /**
         * @var $cb CriteriaBuilder
         */
        $this->cb = $this->app['criteria_builder'];

        /**
         * @var $api_response ResponseBuilder
         */
        $this->rb = $this->app['response_builder'];

        /**
         * @var RecursiveValidator
         */
        $this->validator = $this->app['validator'];
    }

    /**
     * @return string
     */
    protected function getEntityName() : string
    {
        return $this->entity_name;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function post(Request $request)
    {
        /* decode the content of the POST request */
        $fields = json_decode($request->getContent(), true);

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

        /* return a json response */
        return $this->rb->buildJsonResponse($entity, 200);
    }

    /**
     * @param Request $request
     *
     * @param $id
     * @return string
     */
    public function put(Request $request, $id)
    {
        $fields = json_decode($request->getContent(), true);

        /**
         * @var $entity PopulateTrait
         */
        $entity = $this->em->find($this->getEntityName(), $id);

        /* check if the entity exists */
        if(empty($entity)) {
            throw new NotFoundHttpException(sprintf('Entity %s with id %d not found', $this->getEntityName(), $id));
        }

        /* populate the entity */
        $entity->populateFromJsonObject($fields);

        /* validating the entity */
        $errors = $this->validator->validate($entity);

        /* if we have validation errors we return them */
        if(count($errors)) {
            return $this->rb->buildJsonResponse($errors, 422);
        }

        $this->em->persist($entity);
        $this->em->flush($entity);

        return $this->rb->buildJsonResponse($entity, 200);
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete($id)
    {
        /**
         * @var $entity PopulateTrait
         */
        $entity = $this->em->find($this->getEntityName(), $id);

        if(empty($entity)) {
            throw new NotFoundHttpException(sprintf('Entity %s with id %d not found', $this->getEntityName(), $id));
        }

        $this->em->remove($entity);
        $this->em->flush($entity);

        return $this->rb->buildJsonResponse(null, 204);
    }

    /**
     * @param $id
     *
     * @return mixed|string
     */
    public function getSingle($id)
    {
        $entity = $this->em->getRepository($this->getEntityName())->find($id);

        if(empty($entity)) {
            throw new NotFoundHttpException(sprintf('Entity %s with id %d not found', $this->getEntityName(), $id));
        }

        return $this->rb->buildJsonResponse($entity);
    }

    /**
     * @param Request $request
     *
     * @return mixed|string
     */
    public function getMultiple(Request $request)
    {
        $params = $this->cb->build($this->getEntityName(), $request->query->all());

        $repository = $this->em->getRepository($this->getEntityName());
        $entities = $repository->findBy($params['criteria'], $params['order_by'], $params['limit'], $params['offset']);

        return $this->rb->buildJsonResponse($entities);
    }
}