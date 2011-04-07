<?php

class Application_Service_Model extends Skaya_Model_Service_Abstract
{

    public function create($data = array ())
    {
        return new Application_Model_Model($data);
    }

    public function getModelById($id)
    {
        $modelData = $this->_mappers->model->getModelById($id);
        return self::create($modelData);
    }

    public function getModels($order = null, $count = null, $offset = null)
    {
        $modelsBlob = $this->_mappers->model->getModels($order, $count, $offset);
        return new Model_Collection_Models($modelsBlob);
    }

    public function getModelsPaginator($order = null)
    {
        $paginator = $this->_mappers->model->getModelsPaginator($order);
        $paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Models'));
        return $paginator;
    }


}

