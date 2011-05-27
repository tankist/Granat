<?php

class Model_Fabric extends Skaya_Model_Abstract {

	protected $_modelName = 'Fabric';

	/**
	 * @var Model_FabricPhoto
	 */
	protected $_photo;

	/**
	 * @return Model_FabricPhoto
	 */
	public function getPhoto() {
		if (!$this->_photo && !empty($this->photo)) {
			$this->_photo = new Model_FabricPhoto();
			$this->_photo->setFilename($this->photo);
		}
		return $this->_photo;
	}
}