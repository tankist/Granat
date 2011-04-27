<?php
/**
 * @property Zend_Form_Element_Text $email
 * @property Zend_Form_Element_Password $password
 * @property Zend_Form_Element_Checkbox $is_remember
 * @property Zend_Form_Element_Hidden $referer
 * @property Zend_Form_Element_Button $login
 */
class Admin_Form_Login extends Admin_Form_Abstract {

	public function init() {
		$this
			->addElement('text', 'email', array('label' => 'Email:', 'required' => true, 'autoInsertNotEmptyValidator' => false))
			->addElement('password', 'password', array('label' => 'Password:', 'required' => true, 'autoInsertNotEmptyValidator' => false))
			->addElement('checkbox', 'is_remember', array('label' => 'Remember me'))
			->addElement('hidden', 'referer')
			->addElement('submit', 'login', array('label' => 'Login'));
	}

	public function prepareDecorators() {
		$this->setElementDecorators(array('ViewHelper'));
		$this->setDecorators(array(
			new Zend_Form_Decorator_ViewScript(array('viewScript' => 'forms/login.phtml')), 'FormErrors', 'Form'
		));
		return parent::prepareDecorators();
	}

}

?>
