<?php

class Sch_Form extends Zend_Form {

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
     * @param Entities\AbstractEntity $entity
     * @return Sch_Form
     */
    public function populateEntity(\Entities\AbstractEntity $entity)
    {
        $values = array();
        foreach ($this->getElements() as $elementName => /** @var Zend_Form_Element $element */$element) {
            if (isset($entity->{$elementName})) {
                $values[$elementName] = $entity->{$elementName};
            }
        }
        foreach ($this->getSubForms() as $subFormName => /** @var Zend_Form_SubForm $subForm */$subForm) {
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
        foreach ($this->getElements() as $elementName => $element) {
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
        foreach ($this->getSubForms() as $subFormName => $subForm) {
            $prepareMethodName = '_prepare' . ucfirst(Zend_Filter::filterStatic($subFormName, 'Word_UnderscoreToCamelCase')) . 'SubformDecorators';
            if (method_exists($this, $prepareMethodName)) {
                call_user_func(array($this, $prepareMethodName), $subForm);
            }
            if (method_exists($subForm, 'prepareDecorators')) {
                call_user_func(array($subForm, 'prepareDecorators'));
            }
        }
        return $this;
    }

}
