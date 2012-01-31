<?php

namespace Repository;

/**
 * @class Model
 */
class Model extends AbstractRepository
{

    protected $_alias = 'm';

    /**
     * @param int $count
     * @return \Entities\Model[]
     */
    public function getRandomModels($count = null)
    {
        $subQueryBuilder = $this->createQueryBuilder('m')->select('MAX(m.id)');
        $maxId = $subQueryBuilder->getQuery()->getSingleScalarResult();
        $queryBuilder = $this->createQueryBuilder('m');
        $queryBuilder->where('m.id >= :maxId')->setParameter('maxId', rand(1, $maxId));
        $queryBuilder->setMaxResults($count?:1);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param array $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQuery($params = array())
    {
        $queryBuilder = parent::findAllQuery($params);
        if (array_key_exists('category_id', $params) && $params['category_id'] > 0) {
            $queryBuilder
                ->andWhere('m.category = :category')
                ->setParameter('category', $params['category_id']);
        }
        if (array_key_exists('collection_id', $params) && $params['collection_id'] > 0) {
            $queryBuilder
                ->andWhere('m.collection = :collection')
                ->setParameter('collection', $params['collection_id']);
        }
        return $queryBuilder;
    }

}
