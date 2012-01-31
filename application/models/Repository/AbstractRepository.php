<?php

namespace Repository;

/**
 * @class AbstractRepository
 */
class AbstractRepository extends \Doctrine\ORM\EntityRepository
{

    protected $_alias;

    /**
     * @param array $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQuery($params = array())
    {
        $queryBuilder = $this->createQueryBuilder($this->_alias);
        if (is_array($params)) {
            if (array_key_exists('order', $params) && !empty($params['order'])) {
                $order = $params['order'];
                $orderType = (array_key_exists('orderType', $params))?$params['orderType']:'ASC';
                $queryBuilder->orderBy($order, $orderType);
            }
            if (array_key_exists('limit', $params)) {
                $queryBuilder->setMaxResults((int)$params['limit']);
            }
            if (array_key_exists('offset', $params)) {
                $queryBuilder->setFirstResult((int)$params['offset']);
            }
        }
        return $queryBuilder;
    }

}
