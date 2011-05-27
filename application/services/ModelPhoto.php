<?php

class Service_ModelPhoto extends Skaya_Model_Service_Abstract {

    /**
     * @var Model_Mapper_Db_ModelPhoto
     */
    protected $_mapper;

    protected function __construct() {
        parent::__construct();
        $this->_mapper = new Model_Mapper_Decorator_Cache_ModelPhoto($this->_mappers->modelPhoto);
    }

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_ModelPhoto($data);
	}

	public function getPhotoById($id) {
		$photoData = $this->_mapper->getPhotoById($id);
		return new Model_ModelPhoto($photoData);
	}

	public function getPhotos($order = null, $count = null, $offset = null) {
		$photosBlob = $this->_mapper->getPhotos($order, $count, $offset);
		return new Model_Collection_ModelPhotos($photosBlob);
	}

	public function getPhotosPaginator($order = null) {
		$paginator = $this->_mapper->getPhotosPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_ModelPhotos'));
		return $paginator;
	}

}