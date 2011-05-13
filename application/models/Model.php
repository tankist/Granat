<?php
/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $collection_id
 * @property int $category_id
 * @property boolean $mainPhotoId
 * @property int $order
 */
class Model_Model extends Skaya_Model_Abstract {

	protected $_modelName = 'Model';

	/**
	 * @var Model_Category
	 */
	protected $_category;

	/**
	 * @var Model_Collection
	 */
	protected $_collection;

	/**
	 * @var Model_Photo
	 */
	protected $_mainPhoto;

	/**
	 * @param Model_Photo $photo
	 * @return Model_Model
	 */
	public function addPhoto(Model_Photo $photo) {
		$photo->model_id = $this->id;
		$photo->save();
		return $this;
	}

	/**
	 * @param  $photo_id
	 * @return Model_Photo
	 */
	public function getPhotoById($photo_id) {
		$photoBlob = $this->mappers->photo->getModelPhotoById($this->id, $photo_id);
		return new Model_Photo($photoBlob);
	}

	/**
	 * @param null $order
	 * @param null $count
	 * @param null $offset
	 * @return Model_Collection_Photos
	 */
	public function getPhotos($order = null, $count = null, $offset = null) {
		$photosBlob = $this->mappers->photo->getModelPhotos($this->id, $order, $count, $offset);
		return new Model_Collection_Photos($photosBlob);
	}

	/**
	 * @param null $order
	 * @return Skaya_Paginator
	 */
	public function getPhotosPaginator($order = null) {
		$paginator = $this->mappers->photo->getModelPhotosPaginator($this->id, $order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Photos'));
		return $paginator;
	}

	/**
	 * @throws Skaya_Model_Exception
	 * @param Model_Photo $photo
	 * @return Skaya_Model_Abstract
	 */
	public function setMainPhoto(Model_Photo $photo) {
		if ($photo->model_id != $this->id) {
			throw new Skaya_Model_Exception('This photo is not belongs to this model');
		}
		$this->mainPhotoId = $photo->id;
		$this->_mainPhoto = $photo;
		return $this->save();
	}

	/**
	 * @return Model_Photo
	 */
	public function getMainPhoto() {
		if (empty($this->_mainPhoto)) {
			$this->_mainPhoto = Service_Photo::create();
			if ($this->mainPhotoId > 0) {
				$this->_mainPhoto = $this->getPhotoById($this->mainPhotoId);
			}
			else {
				$photos = $this->getPhotos(null, 1);
				if (count($photos) > 0) {
					$this->_mainPhoto = $photos[0];
				}
			}
		}
		return $this->_mainPhoto;
	}

	/**
	 * @param Model_Category $category
	 * @return Skaya_Model_Abstract
	 */
	public function setCategory(Model_Category $category) {
		$this->category_id = $category->id;
		$this->_category = $category;
		return $this->save();
	}

	/**
	 * @return Model_Category
	 */
	public function getCategory() {
		if (empty($this->_category)) {
			$this->_category = Skaya_Model_Service_Abstract::factory('Category')->getCategoryById($this->category_id);
		}
		return $this->_category;
	}

	/**
	 * @param Model_Collection $collection
	 * @return Skaya_Model_Abstract
	 */
	public function setCollection(Model_Collection $collection) {
		$this->_collection = $collection;
		$this->collection_id = $collection->id;
		return $this->save();
	}

	/**
	 * @return Model_Collection
	 */
	public function getCollection() {
		if (empty($this->_collection)) {
			$this->_collection = Skaya_Model_Service_Abstract::factory('Collection')->getCollectionById($this->collection_id);
		}
		return $this->_collection;
	}

	/**
	 * @return Model_Model
	 */
	public function resetCollection() {
		unset($this->_collection);
		return $this;
	}

	/**
	 * @return Model_Model
	 */
	public function resetCategory() {
		unset($this->_category);
		return $this;
	}

	/**
	 * @return Model_Model
	 */
	public function getPreviousModel() {
		return new self($this->getMapper()->getPreviousModel($this->id));
	}

	/**
	 * @return Model_Model
	 */
	public function getNextModel() {
		return new self($this->getMapper()->getNextModel($this->id));
	}

}