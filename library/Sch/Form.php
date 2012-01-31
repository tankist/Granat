<?php

/**
 * @class Sch_Form
 */
class Sch_Form extends Zend_Form
{

    /**
     * @var array
     */
    protected $_elementLabels = array();

    /**
     * @return Sch_Form
     */
    public function prepareDecorators()
    {
        return $this
            ->_prepareElementsDecorators()
            ->_prepareSubformsDecorators();
    }

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this
            ->addPrefixPath('Sch_Form_Decorator', 'Sch/Form/Decorator', 'decorator')
            ->addPrefixPath('Sch_Form_Element', 'Sch/Form/Element', 'element')
            ->addElementPrefixPath('Sch_Form_Decorator', 'Sch/Form/Decorator', 'decorator')
            ->addDisplayGroupPrefixPath('Sch_Form_Decorator', 'Sch/Form/Decorator')
            ->setDefaultDisplayGroupClass('Sch_Form_DisplayGroup');
        parent::__construct($options);
    }

    /**
     * @param Entities\AbstractEntity $entity
     * @return Sch_Form
     */
    public function populateEntity(\Entities\AbstractEntity $entity)
    {
        $values = array();
        foreach ($this->getElements() as $elementName => /** @var Zend_Form_Element $element */
                 $element) {
            $populateMethodName = '_populate' . ucfirst(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase')) . 'Entity';
            if (method_exists($this, $populateMethodName)) {
                $values[$elementName] = call_user_func(array($this, $populateMethodName), $entity);
                continue;
            }
            if (isset($entity->{$elementName}) && $value = $entity->{$elementName}) {
                if ($value instanceof \Entities\AbstractEntity && method_exists($value, 'getId')) {
                    $value = $value->getId();
                }
                $values[$elementName] = $value;
            }
        }
        foreach ($this->getSubForms() as $subFormName => /** @var Zend_Form_SubForm $subForm */
                 $subForm) {
            $populateMethodName = '_populate' . ucfirst(Zend_Filter::filterStatic($subFormName, 'Word_UnderscoreToCamelCase')) . 'Entity';
            if (method_exists($this, $populateMethodName)) {
                $values[$subFormName] = call_user_func(array($this, $populateMethodName), $entity);
                continue;
            }
            if (isset($entity->{$subFormName})) {
                $data = $entity->{$subFormName};
                if ($subForm instanceof Sch_Form && $data instanceof \Entities\AbstractEntity) {
                    $subForm->populateEntity($entity->{$subFormName});
                }
                else {
                    $values[$subFormName] = $entity->{$subFormName};
                }
            }
        }
        return $this->populate($values);
    }

    /**
     * @param array $values
     * @return Sch_Form
     */
    public function populate(array $values)
    {
        foreach ($values as $elementName => $elementValue) {
            $populateMethodName = '_populate' . ucfirst(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase'));
            if (method_exists($this, $populateMethodName)) {
                $values = call_user_func(array($this, $populateMethodName), $values);
            }
        }

        return parent::populate($values);
    }

    /**
     * @return Sch_Form
     */
    protected function _prepareElementsDecorators()
    {
        $labels = $this->getElementLabels();
        foreach ($this->getElements() as $elementName => $element) {
            if (array_key_exists($elementName, $labels) && !$element->getLabel()) {
                $element->setLabel($labels[$elementName]);
            }
            $prepareMethodName = '_prepare' . ucfirst(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase')) . 'Decorators';
            if (method_exists($this, $prepareMethodName)) {
                call_user_func(array($this, $prepareMethodName), $element);
            }
        }
        return $this;
    }

    /**
     * @return Sch_Form
     */
    protected function _prepareSubformsDecorators()
    {

        foreach ($this->getSubForms() as $subFormName => /** @var $subForm Zend_Form */
                 $subForm) {
            $prepareMethodName = '_prepare' .
                ucfirst(Zend_Filter::filterStatic($subFormName, 'Word_UnderscoreToCamelCase')) .
                'SubformDecorators';
            if (method_exists($subForm, 'prepareDecorators')) {
                call_user_func(array($subForm, 'prepareDecorators'));
            }
            if (method_exists($this, $prepareMethodName)) {
                call_user_func(array($this, $prepareMethodName), $subForm);
            }
            if ($subForm->getDecorator('Zend_Form_Decorator_Form')) {
                $subForm->removeDecorator('Zend_Form_Decorator_Form');
            }
            if ($subForm->getDecorator('Form')) {
                $subForm->removeDecorator('Form');
            }
        }
        return $this;
    }

    /**
     * @param $elementLabels
     * @return \Sch_Form
     */
    public function setElementLabels($elementLabels)
    {
        $this->_elementLabels = $elementLabels;
        return $this;
    }

    /**
     * @return array
     */
    public function getElementLabels()
    {
        return $this->_elementLabels;
    }

}
