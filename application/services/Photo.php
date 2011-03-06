<?php

class Application_Service_Photo extends Skaya_Model_Service_Abstract
{

    public function create($data = array ())
    {
        return new Application_Model_Photo($data);
    }

    public function getPhotoById($id)
    {
        $photoData = $this->_mappers->photo->getPhotoById($id);
        return self::create($photoData);
    }

    public function getPhotos($order = null, $count = null, $offset = null)
    {
        $photosBlob = $this->_mappers->photo->getPhotos($order, $count, $offset);
        return new Model_Collection_Photos($photosBlob);
    }

    public function getPhotosPaginator($order = null)
    {
        $paginator = $this->_mappers->photo->getPhotosPaginator($order);
        $paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Photos'));
        return $paginator;
    }


}

