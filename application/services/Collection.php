<?php

use \Entities\Collection;

/**
 * @class Service_Collection
 */
class Service_Collection extends Sch_Service_Abstract
{

    protected $_entityName = '\Entities\Collection';

    /**
     * @param array $params
     * @return Zend_Paginator
     */
    public function getPaginator($params = array())
    {
        return parent::getPaginator($this->getRepository()->findAllQuery($params));
    }

    /**
     * @param array $params
     * @return \Entities\Collection[]
     */
    public function getNonEmptyCollections($params = array())
    {
        $params['nonEmpty'] = true;
        return $this->getRepository()->findAllQuery($params)->getQuery()->getResult();
    }

}
