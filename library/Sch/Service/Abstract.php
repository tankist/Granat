<?php

use \Entities\AbstractEntity, Repository\AbstractRepository;

/**
 * @class Sch_Service_Abstract
 */
abstract class Sch_Service_Abstract
{

    /**
     * EntityManager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    protected $_entityName;

    /**
     * @var \Repository\AbstractRepository
     */
    protected $_repository;

    /**
     * @param Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @return Sch_Service_Abstract
     */
    public function setEntityManager($em)
    {
        $this->_em = $em;
        return $this;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }

    /**
     * @throws InvalidArgumentException|ReflectionException
     * @return self
     */
    public function create()
    {
        $args = func_get_args();
        if (!class_exists($this->_entityName, true)) {
            throw new ReflectionException('Class ' . $this->_entityName . ' not found');
        }
        $class = new ReflectionClass($this->_entityName);
        if ($class->getConstructor()->getNumberOfRequiredParameters() > count($args)) {
            throw new InvalidArgumentException('Not enough parameters');
        }
        return $class->newInstanceArgs($args);
    }

    /**
     * @param \Entities\AbstractEntity $entity
     * @param array $data
     * @return Sch_Service_Abstract
     */
    public function update($entity, $data = array())
    {
        $entity->populate($data);
        return $this;
    }

    /**
     * @param \Entities\AbstractEntity $entity
     * @return Sch_Service_Abstract
     */
    public function save($entity)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();
        return $this;
    }

    /**
     * @param \Entities\AbstractEntity $entity
     * @return Sch_Service_Abstract
     */
    public function delete($entity)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this;
    }

    /**
     * @return \Repository\AbstractRepository
     */
    public function getRepository()
    {
        if (!$this->_repository && $this->_entityName) {
            $this->_repository = $this->getEntityManager()
                ->getRepository($this->_entityName);
        }
        return $this->_repository;
    }

    /**
     * @param int $id
     * @return \Entities\AbstractEntity
     */
    public function getById($id)
    {
        $entity = $this->getRepository()->findOneBy(array('id' => (int)$id));
        return $entity;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param array|\Doctrine\ORM\QueryBuilder $params
     * @return Zend_Paginator
     */
    public function getPaginator($params = array())
    {
        if (!($params instanceof \Doctrine\ORM\QueryBuilder)) {
            $query = $this->getRepository()->createQueryBuilder('t');
            if (array_key_exists('order', $params) && !empty($params['order'])) {
                $type = (array_key_exists('orderType', $params)) ? $params['orderType'] : 'ASC';
                $query->orderBy($params['order'], $type);
                unset($params['order'], $params['orderType']);
            }
        }
        else {
            $query = $params;
        }
        $paginator = new Zend_Paginator(
            new \DoctrineExtensions\Paginate\PaginationAdapter($query->getQuery())
        );
        return $paginator;
    }

}
