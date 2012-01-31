<?php
/**
 * @property Zend_Form_Element_Hidden $id
 * @property Zend_Form_Element_Text $name
 * @property Zend_Form_Element_Textarea $description
 * @property Zend_Form_Element_Radio $mainModelId
 * @property Zend_Form_Element_Button $submit
 */
class Admin_Form_Collection extends Sch_Form
{

    /**
     * @var array
     */
    protected $_models = array();

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

        $description = new Zend_Form_Element_Textarea('description');

        $mainModelId = new Zend_Form_Element_Radio('main_model_id');
        $mainModelId->setMultiOptions($this->getModels());

        $this->addElements(array($id, $title, $description, $mainModelId));
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
            new Sch_Form_Decorator_ViewScript(array('viewScript' => 'forms/collection.phtml')),
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

    /**
     * @param $models
     * @return \Admin_Form_Collection
     */
    public function setModels($models)
    {
        $this->_models = $models;
        return $this;
    }

    /**
     * @return array
     */
    public function getModels()
    {
        return $this->_models;
    }

}
