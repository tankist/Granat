<?php

namespace Repository;

/**
 * @class Collection
 */
class Collection extends AbstractRepository
{

    protected $_alias = 'c';

    /**
     * @param array $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQuery($params = array())
    {
        $queryBuilder = parent::findAllQuery($params);
        if (array_key_exists('nonEmpty', $params) && $params['nonEmpty'] === true) {
            $queryBuilder->innerJoin('c.models', 'm');
        }
        return $queryBuilder;
    }

}
