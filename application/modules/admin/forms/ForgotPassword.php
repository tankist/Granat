<?php
/**
 * @property Zend_Form_Element_Text $username
 * @property Zend_Form_Element_Button $forgot
 */

class Admin_Form_ForgotPassword extends Sch_Form
{

    public function init()
    {
        parent::init();

        $this->addElement('text', 'username', array('label' => 'Username:', 'required' => true))
            ->addElement('submit', 'forgot', array('label' => 'Get Password'));
    }

    public function prepareDecorators()
    {
        $this->setElementDecorators(array('ViewHelper'));
        $this->setDecorators(array(
            new Zend_Form_Decorator_ViewScript(array('viewScript' => 'forms/forgot.phtml')), 'FormErrors', 'Form'
        ));
        return parent::prepareDecorators();
    }
}

?>
