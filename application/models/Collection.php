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
	 * @var Model_Collection_Models
	 */
	protected $_models;

    /**
     * @var Model_Mapper_Db_Collection
     */
    protected $_mapper;

    /**
     * @var Model_Mapper_Db_Model
     */
    protected $_modelMapper;

    /**
     * @var Model_Mapper_Db_Category
     */
    protected $_categoryMapper;

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
			if ($this->mainModelId > 0) {
				$this->_mainModel = Skaya_Model_Service_Abstract::factory('Model')
					->getModelById($this->mainModelId);
			}
			else {
				$this->_mainModel = Service_Model::create();
			}
			if ($this->_mainModel->isEmpty()) {
				$models = $this->getModels(null, 1);
				if (count($models) > 0) {
					$this->_mainModel = $models[0];
				}
			}
		}
		return $this->_mainModel;
	}

	/**
	 * @param null $order
	 * @param null $count
	 * @param null $offset
	 * @return Model_Collection_Categories
	 */
	public function getCategories($order = null, $count = null, $offset = null) {
		if (empty($this->_categories)) {
			$this->_categories = new Model_Collection_Categories(
				$this->getCategoryMapper()->getCollectionCategories($this->id, $order, $count, $offset)
			);
		}
		return $this->_categories;
	}

	/**
	 * @param null $order
	 * @param null $count
	 * @param null $offset
	 * @return Model_Collection_Models
	 */
	public function getModels($order = null, $count = null, $offset = null) {
		if (empty($this->_models)) {
			$this->_models = new Model_Collection_Models(
				$this->getModelMapper()->getCollectionModels($this->id, $order, $count, $offset)
			);
		}
		return $this->_models;
	}

	public function getModelsPaginator($order = null) {
		$paginator = $this->getModelMapper()->getCollectionModelsPaginator($this->id, $order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Models'));
		return $paginator;
	}

	public function getCategoryModels(Model_Category $category, $order = null, $count = null, $offset = null) {
		$modelsBlob = $this->getModelMapper()->getCollectionModelsByCategory($this->id, $category->id, $order, $count, $offset);
		return new Model_Collection_Models($modelsBlob);
	}

	public function getCategoryModelsPaginator(Model_Category $category, $order = null) {
		$paginator = $this->getModelMapper()->getCollectionModelsPaginatorByCategory($this->id, $category->id, $order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Models'));
		return $paginator;
	}

    /**
     * @return Model_Mapper_Decorator_Cache_Collection
     */
    public function getMapper() {
        if (!$this->_mapper) {
            $this->_mapper = new Model_Mapper_Decorator_Cache_Collection(parent::getMapper());
        }
        return $this->_mapper;
    }

    /**
     * @return Model_Mapper_Db_Model
     */
    public function getModelMapper() {
        if (!$this->_modelMapper) {
            $this->_modelMapper = new Model_Mapper_Decorator_Cache_Model($this->mappers->model);
        }
        return $this->_modelMapper;
    }

    /**
     * @return Model_Mapper_Db_Category
     */
    public function getCategoryMapper() {
        if (!$this->_categoryMapper) {
            $this->_categoryMapper = new Model_Mapper_Decorator_Cache_Category($this->mappers->category);
        }
        return $this->_categoryMapper;
    }

}