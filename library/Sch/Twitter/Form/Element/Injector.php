<?php

/**
 * @class Sch_Twitter_Form_Element_Injector
 */
class Sch_Twitter_Form_Element_Injector
{

    /**
     * @var Zend_Form_Element
     */
    protected $_element;

    /**
     * @param Zend_Form_Element $element
     */
    public function __construct(Zend_Form_Element $element)
    {
        $this->setElement($element);
    }

    /**
     * @param bool $overwrite
     * @return Sch_Twitter_Form_Element_Injector
     */
    public function injectDecorators($overwrite = true)
    {
        $element = $this->getElement();
        if ($overwrite) {
            $element->clearDecorators();
        }
        $element->addDecorator('ViewHelper')
            ->addDecorator('Errors')
            ->addDecorator('Description', array('tag' => 'p', 'class' => 'description help-block'))
            ->addDecorator(
                array('controls' => 'HtmlTag'),
                array(
                    'tag' => 'div',
                    'class' => 'controls'
                )
            )
            ->addDecorator('Label', array('class' => 'control-label'))
            ->addDecorator(
                array('control-group' => 'HtmlTag'),
                array(
                    'tag' => 'div',
                    'class' => 'control-group',
                    'id' => array('callback' => array(get_class($element), 'resolveElementId'))
                )
            );
        return $this;
    }

    /**
     * @param \Zend_Form_Element $element
     * @return self
     */
    public function setElement(Zend_Form_Element $element)
    {
        $this->_element = $element;
        return $this;
    }

    /**
     * @return \Zend_Form_Element
     */
    public function getElement()
    {
        return $this->_element;
    }

}
