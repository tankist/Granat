<?php

class Sch_Paginator_Adapter_Doctrine implements Zend_Paginator_Adapter_Interface
{

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $_queryBuilder;

    public function __construct(\Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        $this->_queryBuilder = $queryBuilder;
    }

    /**
     * @param $offset
     * @param $itemsCount
     * @return array
     */
    public function getItems($offset, $itemsCount)
    {
        $posts = array();
        $results = $this->_queryBuilder->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($itemsCount)
            ->getResult();
        foreach ($results as $row) {
            if ($row instanceof \Entities\AbstractEntity) {
                $posts[] = $row;
                continue;
            }
            if ($row[0] instanceof \Entities\AbstractEntity) {
                $post = array_shift($row);
                if (count($row) > 0) {
                    foreach ($row as $name => $value) {
                        $setter = 'set' . ucfirst($name);
                        if (method_exists($post, $setter)) {
                            call_user_func(array($post, $setter), $value);
                        }
                    }
                }
                $posts[] = $post;
            }
        }

        return $posts;
    }

    /**
     * @return int
     */
    public function count()
    {
        $queryBuilder = clone $this->_queryBuilder;
        $from = $queryBuilder->getDQLPart('from');
        return $queryBuilder
            ->resetDQLParts(array('select', 'orderBy', 'groupBy', 'having'))
            ->addSelect(new \Doctrine\ORM\Query\Expr\Func('COUNT', 'DISTINCT ' . $from[0]->getAlias()))
            ->getQuery()
            ->getSingleScalarResult();
    }

}
