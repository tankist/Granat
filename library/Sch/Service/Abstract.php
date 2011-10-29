<?php
use \Entities\AbstractEntity;

class Sch_Service_Abstract
{

	/**
	 * EntityManager
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $_em;

    protected $_entityName;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $_repository;

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
    public function delete($entity) {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
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

    public function getAll()
    {
        return $this->getRepository()->findAll();
    }

}
