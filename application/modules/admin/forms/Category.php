<?php
/**
 * @property Zend_Form_Element_Hidden $id
 * @property Zend_Form_Element_Text $name
 * @property Zend_Form_Element_Button $submit
 */
class Admin_Form_Category extends Sch_Form
{

    /**
     * @var array
     */
    protected $_elementLabels = array(
        'title' => 'Title',
        'description' => 'Description'
    );

    /**
     * @var Sch_Twitter_Form_Injector
     */
    private $_injector;

    public function init()
    {
        $this->_injector = new Sch_Twitter_Form_Injector($this);
        $this->_injector->injectInit();

        $id = new Zend_Form_Element_Hidden('id');

        $title = new Zend_Form_Element_Text('title');
        $title->setRequired(true);

        $this->addElements(array($id, $title));
    }

    /**
     * @return Sch_Form
     */
    public function prepareDecorators()
    {
        $this->_injector
            ->setType(Sch_Twitter_Form_Injector::FORM_TYPE_HORIZONTAL)
            ->injectMarkup()
            ->injectElements();

        $this->setDecorators(array(
            'FormErrors',
            new Sch_Form_Decorator_ViewScript(array('viewScript' => 'forms/category.phtml')),
            'Form'
        ));

        return parent::prepareDecorators();
    }

    /**
     * @param Zend_Form_Element_Hidden $id
     */
    protected function _prepareIdDecorators(Zend_Form_Element_Hidden $id)
    {
        $id->setDecorators(array('ViewHelper'));
    }

}
