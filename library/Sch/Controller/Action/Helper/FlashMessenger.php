<?php

class Sch_Controller_Action_Helper_FlashMessenger extends Zend_Controller_Action_Helper_FlashMessenger
{

    public function addErrorsFromForm(Zend_Form $form, $element = null)
    {
        $errors = $form->getMessages($element, true);
        foreach ($errors as $name => $elementErrors) {
            if ($form->$name instanceof Zend_Form) {
                $this->addErrorsFromForm($form->$name);
            } else {
                $subElement = $form->getElement($name);
                if ($subElement instanceof Zend_Form_Element) {
                    $label = $subElement->getLabel();
                    $this->addMessage(array(
                        'message' => $label . ': ' . join('; ', $elementErrors),
                        'status' => 'fail'
                    ));
                }
            }
        }
        return $this;
    }

    public function __call($name, $parameters)
    {
        $message = array_shift($parameters);
        $this->addMessage(array(
            'message' => $message,
            'status' => $name
        ));
        return $this;
    }

}
