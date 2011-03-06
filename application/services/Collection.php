<?php

class Application_Service_Collection extends Skaya_Model_Service_Abstract
{

    public function create($data = array ())
    {
        return new Application_Model_Collection($data);
    }

    public function getCollectionById($id)
    {
        $collectionData = $this->_mappers->collection->getCollectionById($id);
        return self::create($collectionData);
    }

    public function getCollections($order = null, $count = null, $offset = null)
    {
        $collectionsBlob = $this->_mappers->collection->getCollections($order, $count, $offset);
        return new Model_Collection_Collections($collectionsBlob);
    }

    public function getCollectionsPaginator($order = null)
    {
        $paginator = $this->_mappers->collection->getCollectionsPaginator($order);
        $paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Collections'));
        return $paginator;
    }


}

