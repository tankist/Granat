<?php
/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $collection_id
 * @property boolean $isCollectionTitle
 * @property int $order
 */
class Model_Model extends Skaya_Model_Abstract {

	protected $_modelName = 'Model';

	public function addPhoto(Model_Photo $photo) {
		$photo->model_id = $this->id;
		$photo->save();
		return $this;
	}

	public function getPhotoById($photo_id) {
		/**
		 * @var Model_Photo $photo
		 */
		$photo = Skaya_Model_Service_Abstract::factory('Photo')->getPhotoById($photo_id);
		if ($photo->isEmpty() || $photo->model_id != $this->id) {
			$photo = new Model_Photo();
		}
		return $photo;
	}

	public function getPhotos($order = null, $count = null, $offset = null) {
		$photosBlob = $this->mappers->photo->getModelPhotos($this->id, $order, $count, $offset);
		return new Model_Collection_Photos($photosBlob);
	}

	public function getPhotosPaginator($order = null) {
		$paginator = $this->mappers->photo->getModelPhotosPaginator($this->id, $order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Photos'));
		return $paginator;
	}

}