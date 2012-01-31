<?php

use \Entities\Model, \Entities\Model\Photo;

/**
 * @class Service_Model
 */
class Service_Model extends Sch_Service_Abstract
{

    protected $_entityName = '\Entities\Model';

    /**
     * @param int $count
     * @return \Entities\Model[]
     */
    public function getRandomModels($count = null)
    {
        return $this->getRepository()->getRandomModels($count);
    }

    /**
     * @param array $params
     * @return Zend_Paginator
     */
    public function getPaginator($params = array())
    {
        return parent::getPaginator($this->getRepository()->findAllQuery($params));
    }

}
