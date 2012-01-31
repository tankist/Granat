<?php

/**
 * @class Admin_Form_Login
 */
class Admin_Form_Login extends Sch_Form
{

    /**
     *
     */
    public function init()
    {
        $email = new Zend_Form_Element_Text('email');
        $email->setRequired(true);

        $password = new Zend_Form_Element_Password('password');
        $password->setRequired(true);

        $remember = new Zend_Form_Element_Checkbox('remember');

        $this->addElements(array($email, $password, $remember));
    }

    /**
     * @return Sch_Form
     */
    public function prepareDecorators()
    {
        $this->setElementDecorators(array(
            'ViewHelper',
            'input' => new Zend_Form_Decorator_HtmlTag(array('tag' => 'div', 'class' => 'input')),
            'Label',
            'clear' => new Zend_Form_Decorator_HtmlTag(array('tag' => 'div', 'class' => 'clearfix')),
        ));
        $this->setDecorators(array(
            new Sch_Form_Decorator_FormErrors(),
            new Sch_Form_Decorator_ViewScript(array('viewScript' => 'forms/login.phtml')),
            'Form'
        ));
        return parent::prepareDecorators();
    }

    /**
     * @param Zend_Form_Element_Checkbox $remember
     */
    public function _prepareRememberDecorators(Zend_Form_Element_Checkbox $remember)
    {
        $remember->setDecorators(array('ViewHelper'));
    }

}

