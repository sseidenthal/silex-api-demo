<?php

namespace App\Builders;

use Pimple\Container;

class CriteriaBuilder
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
     * @return array
     */
    private function getFieldsToIgnore(): array
    {
        /* this are reserved words so we skip them */
        $ignore = [];
        $ignore[] = 'limit';
        $ignore[] = 'offset';
        $ignore[] = 'order';

        return $ignore;
    }

    /**
     * @param $entity_name
     * @param array $params
     *
     * @return array
     */
    public function build($entity_name, array $params): array
    {
        $result = [];
        $result['limit'] = $this->buildLimit($params);
        $result['offset'] = $this->buildOffset($params);
        $result['order_by'] = $this->buildOrder($params);
        $result['criteria'] = $this->buildCriteria($entity_name, $params);

        return $result;
    }

    /**
     * @return array
     */
    public function buildOrder(array $params): array
    {
        $order = $this->app['params']['app']['defaults']['order'];

        if (array_key_exists('order', $params)) {

            $order = [];

            if(preg_match('/,/', $params['order'])) {

                $fields = explode(',', $params['order']);

                foreach ($fields as $field) {
                    $this->parseOrderField($field, $order);
                }

            } else {

                $this->parseOrderField($params['order'], $order);

            }

        }

        return $order;
    }

    private function parseOrderField($field, &$order)
    {
        $direction = 'ASC';

        if(strpos($field, '-') !== false) {
            $direction = 'DESC';
            $field = substr($field, 1, strlen($field));
        }

        $order[$field] = $direction;
    }

    /**
     * @return int
     */
    public function buildLimit(array $params): int
    {
        $limit = $this->app['params']['app']['defaults']['limit'];

        if (array_key_exists('limit', $params)) {
            $limit = (int)$params['limit'];
        }

        return $limit;
    }

    /**
     * @return int
     */
    public function buildOffset(array $params): int
    {
        $offset = $this->app['params']['app']['defaults']['offset'];

        if (array_key_exists('offset', $params)) {
            $offset = (int)$params['offset'];
        }

        return $offset;
    }

    /**
     * @param $entity_name
     * @param array $params
     *
     * @return mixed
     */
    public function buildCriteria($entity_name, array $params): array
    {
        $this->checkFields($entity_name, $params);

        $criteria = [];

        foreach ($params as $param => $value) {

            if (!in_array($param, $this->getFieldsToIgnore())) {

                if (preg_match('/,/', $value)) {
                    $criteria[$param] = explode(',', $value);
                } else {
                    $criteria[$param] = $value;
                }
            }
        }


        return $criteria;
    }

    /**
     * @param $entity_name
     * @param array $params
     *
     * @throws \Exception
     */
    private function checkFields($entity_name, array $params)
    {
        $class = new \ReflectionClass($entity_name);

        foreach ($params as $param => $value) {

            if (!in_array($param, $this->getFieldsToIgnore())) {

                $property = $class->getProperty($param);
                $name = $property->getName();
                $docblock = $property->getDocComment();

                if (!preg_match('/@ORM/', $docblock)) {
                    throw new \Exception(sprintf('entity %s hs no ORM definition on field %s', $entity_name, $name));
                }
            }

        }
    }

}