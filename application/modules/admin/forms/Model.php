<?php
/**
 * @property Zend_Form_Element_Hidden $id
 * @property Zend_Form_Element_Text $name
 * @property Zend_Form_Element_Textarea $description
 * @property Zend_Form_Element_Select $collection_id
 * @property Zend_Form_Element_Checkbox $is_collection_title
 * @property Zend_Form_Element_Button $submit
 */
class Admin_Form_Model extends Admin_Form_Abstract {

	protected $_images = array();

	protected $_collections = array();

	public function init() {
		$this
			->addElement('hidden', 'id', array('label' => 'id'))
			->addElement('text', 'name', array('label' => 'Name:', 'required' => true, 'class' => 'key-source'))
			->addElement('text', 'key', array('label' => 'Short Key:', 'required' => true, 'class' => 'key-target'))
			->addElement('textarea', 'description', array('label' => 'Description:', 'rows' => 10, 'cols' => 40))
			->addElement('select', 'collection_id', array('label' => 'Collection:', 'required' => true, 'multiOptions' => $this->getCollections()))
			->addElement('checkbox', 'is_collection_title', array('label' => 'Main Collection Model:'))
			->addElement($this->_getImageElement(), 'modelTitle');
	}

	protected function _getImageElement() {
		$images = $this->getImages();
		$keys = array_keys((array) $images);
		$images_el = new Skaya_Form_Element_ProductImage('modelTitle', array(
			'multiOptions' => array_fill_keys($keys, 'Main Image'),
			'images' => $images,
			'label' => 'Main Image'
		));
		return $images_el->setRequired(true);
	}

	public function prepareDecorators() {
		parent::prepareDecorators();
		if ($this->id instanceof Zend_Form_Element){
			$this->id->setDecorators(array('ViewHelper'));
		}
	}

	public function setCollections($collections) {
		$this->_collections = $collections;
		return $this;
	}

	public function getCollections() {
		return $this->_collections;
	}

	public function setImages($images = array()) {
		$this->_images = $images;
		return $this;
	}

	public function getImages() {
		return $this->_images;
	}

}