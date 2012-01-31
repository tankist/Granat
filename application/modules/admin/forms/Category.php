<?php
/**
 * @property Zend_Form_Element_Hidden $id
 * @property Zend_Form_Element_Text $name
 * @property Zend_Form_Element_Button $submit
 */
class Admin_Form_Category extends Sch_Form
{

    public function init()
    {
        $this
            ->addElement('hidden', 'id', array('label' => 'id'))
            ->addElement('text', 'name', array('label' => 'Name:', 'required' => true))
            ->addElement('button', 'submit', array('label' => 'Save', 'type' => 'submit'));

    }

    public function prepareDecorators()
    {
        parent::prepareDecorators();
        if ($this->id instanceof Zend_Form_Element) {
            $this->id->setDecorators(array('ViewHelper'));
        }
    }

}
