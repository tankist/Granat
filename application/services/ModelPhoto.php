<?php

class Service_ModelPhoto extends Skaya_Model_Service_Abstract {

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_ModelPhoto($data);
	}

	public function getPhotoById($id) {
		$photoData = $this->_mappers->modelPhoto->getPhotoById($id);
		return new Model_ModelPhoto($photoData);
	}

	public function getPhotos($order = null, $count = null, $offset = null) {
		$photosBlob = $this->_mappers->modelPhoto->getPhotos($order, $count, $offset);
		return new Model_Collection_ModelPhotos($photosBlob);
	}

	public function getPhotosPaginator($order = null) {
		$paginator = $this->_mappers->modelPhoto->getPhotosPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_ModelPhotos'));
		return $paginator;
	}

}