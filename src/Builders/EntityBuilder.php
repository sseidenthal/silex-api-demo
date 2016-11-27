<?php

namespace App\Builders;

use Pimple\Container;
use Doctrine\Common\Collections\ArrayCollection;

class EntityBuilder
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
     * @param $entity_name
     * @param array $fields
     *
     * @return mixed
     */
    public function build($entity_name, array $fields)
    {
        $entity = new $entity_name;
        $entity = $this->populateEntity($entity, $fields);

        return $entity;
    }

    /**
     * @param $entity_name
     * @param array $fields_
     *
     * @return ArrayCollection
     */
    public function buildMultiple($entity_name, array $fields_): ArrayCollection
    {
        $entities = new ArrayCollection();
        foreach ($fields_ as $fields) {
            $entity = $this->populateEntity(new $entity_name, $fields);
            $entities->add($entity);
        }

        return $entities;
    }

    /**
     * @param $entity
     * @param array $fields
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function populateEntity($entity, array $fields)
    {
        foreach ($fields as $field => $value) {

            $method = 'set' . ucfirst($field);

            if (!method_exists($entity, $method)) {
                throw new \Exception(sprintf('field %s does not exist entity %s', $field, get_class($entity)));
            }

            $entity->{$method}($value);
        }

        return $entity;
    }

}