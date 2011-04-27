<?php

class Admin_Form_Collection extends Admin_Form_Abstract {

	public function init() {
		$this
			->addElement('hidden', 'id', array('label' => 'id'))
			->addElement('text', 'name', array('label' => 'Name:', 'required' => true))
			->addElement('button', 'submit', array('label' => 'submit', 'type' => 'submit'));

	}

	public function prepareDecorators() {
		parent::prepareDecorators();
		if ($this->id instanceof Zend_Form_Element){
			$this->id->setDecorators(array('ViewHelper'));
		}
	}

}