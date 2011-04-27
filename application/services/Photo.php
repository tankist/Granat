<?php

class Service_Photo extends Skaya_Model_Service_Abstract {

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_Photo($data);
	}

	public function getPhotoById($id) {
		$photoData = $this->_mappers->photo->getPhotoById($id);
		return new Model_Photo($photoData);
	}

	public function getPhotos($order = null, $count = null, $offset = null) {
		$photosBlob = $this->_mappers->photo->getPhotos($order, $count, $offset);
		return new Model_Collection_Photos($photosBlob);
	}

	public function getPhotosPaginator($order = null) {
		$paginator = $this->_mappers->photo->getPhotosPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Photos'));
		return $paginator;
	}

}