<?php

class Application_Service_Fabric extends Skaya_Model_Service_Abstract
{

    public function create($data = array ())
    {
        return new Application_Model_Fabric($data);
    }

    public function getFabricById($id)
    {
        $fabricData = $this->_mappers->fabric->getFabricById($id);
        return self::create($fabricData);
    }

    public function getFabrics($order = null, $count = null, $offset = null)
    {
        $fabricsBlob = $this->_mappers->fabric->getFabrics($order, $count, $offset);
        return new Model_Collection_Fabrics($fabricsBlob);
    }

    public function getFabricsPaginator($order = null)
    {
        $paginator = $this->_mappers->fabric->getFabricsPaginator($order);
        $paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Fabrics'));
        return $paginator;
    }


}

