<?php
/**
 * @property Zend_Form_Element_Hidden $id
 * @property Zend_Form_Element_Text $name
 * @property Zend_Form_Element_Textarea $description
 * @property Zend_Form_Element_Radio $mainModelId
 * @property Zend_Form_Element_Button $submit
 */
class Admin_Form_Collection extends Admin_Form_Abstract {

	protected $_models = array();

	public function init() {
		$this
			->addElement('hidden', 'id', array('label' => 'id'))
			->addElement('text', 'name', array('label' => 'Name:', 'required' => true))
			->addElement('textarea', 'description', array('label' => 'Description:'))
			->addElement('radio', 'mainModelId', array('label' => 'Main model', 'multiOptions' => $this->getModels()))
			->addElement('button', 'submit', array('label' => 'Save', 'type' => 'submit'));

	}

	public function prepareDecorators() {
		parent::prepareDecorators();
		if ($this->id instanceof Zend_Form_Element){
			$this->id->setDecorators(array('ViewHelper'));
		}

		$this->setDecorators(array(
			new Zend_Form_Decorator_ViewScript(array('viewScript' => 'forms/collection.phtml')),
			'Form'
		));
	}

	public function setModels($models) {
		$this->_models = $models;
		return $this;
	}

	public function getModels() {
		return $this->_models;
	}

}