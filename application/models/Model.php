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

	public function addImage(Model_Photo $image) {
		$image->model_id = $this->id;
		$image->save();
		return $this;
	}	

}