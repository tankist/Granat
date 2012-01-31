<?php

class Sch_Form_Decorator_FormErrors extends Zend_Form_Decorator_FormErrors
{

    protected $_defaults = array(
        'ignoreSubForms' => false,
        'showCustomFormErrors' => true,
        'onlyCustomFormErrors' => false,
        'markupElementLabelEnd' => '',
        'markupElementLabelStart' => '',
        'markupListEnd' => '',
        'markupListItemEnd' => '',
        'markupListItemStart' => '',
        'markupListStart' => '',
    );

    public function renderLabel(Zend_Form_Element $element, Zend_View_Interface $view)
    {
        return '';
    }

}
