<?php
/**
 * @throws Skaya_Model_Exception
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $order
 * @property int $mainModelId
 */
class Model_Collection extends Skaya_Model_Abstract {

	protected $_modelName = 'Collection';

	/**
	 * @var Model_Model
	 */
	protected $_mainModel;

	/**
	 * @var Model_Collection_Categories
	 */
	protected $_categories;

	/**
	 * @throws Skaya_Model_Exception
	 * @param Model_Model $model
	 * @return Skaya_Model_Abstract
	 */
	public function setMainModel(Model_Model $model) {
		if ($model->collection_id != $this->id) {
			throw new Skaya_Model_Exception('This model is not belongs to this collection');
		}
		$this->mainModelId = $model->id;
		$this->_mainModel = $model;
		return $this->save();
	}

	/**
	 * @return Model_Model
	 */
	public function getMainModel() {
		if (empty($this->_mainModel)) {
			$this->_mainModel = Skaya_Model_Service_Abstract::factory('Model')
				->getModelById($this->mainModelId);
		}
		return $this->_mainModel;
	}

	/**
	 * @return Model_Collection_Categories
	 */
	public function getCategories() {
		if (empty($this->_categories)) {
			$this->_categories = new Model_Collection_Categories(
				$this->mappers->category->getCollectionCategories($this->id)
			);
		}
		return $this->_categories;
	}

}