<?php

/**
 * @class Sch_Twitter_Form_Injector
 */
class Sch_Twitter_Form_Injector
{

    const FORM_TYPE_VERTICAL = 'vertical';

    const FORM_TYPE_HORIZONTAL = 'horizontal';

    const FORM_TYPE_SEARCH = 'search';

    const FORM_TYPE_INLINE = 'inline';

    protected $_types = array(
        self::FORM_TYPE_VERTICAL => array(
            'addAttribs' => array(
                'class' => 'form-vertical'
            )
        ),
        self::FORM_TYPE_HORIZONTAL => array(
            'addAttribs' => array(
                'class' => 'form-horizontal'
            )
        ),
        self::FORM_TYPE_SEARCH => array(
            'addAttribs' => array(
                'class' => 'form-search'
            )
        ),
        self::FORM_TYPE_INLINE => array(
            'addAttribs' => array(
                'class' => 'form-inline'
            )
        ),
    );

    protected $_type = self::FORM_TYPE_VERTICAL;

    /**
     * @var Zend_Form
     */
    protected $_form;

    /**
     * @param Zend_Form $form
     */
    public function __construct(Zend_Form $form)
    {
        $this->setForm($form);
    }

    /**
     * @return \Sch_Twitter_Form_Injector
     */
    public function injectInit()
    {
        $this->getForm()
            ->addPrefixPath('Sch_Twitter_Form_Decorator', 'Sch/Twitter/Form/Decorator', 'decorator')
            ->addPrefixPath('Sch_Twitter_Form_Element', 'Sch/Twitter/Form/Element', 'element')
            ->addElementPrefixPath('Sch_Twitter_Form_Decorator', 'Sch/Twitter/Form/Decorator', 'decorator')
            ->addDisplayGroupPrefixPath('Sch_Twitter_Form_Decorator', 'Sch/Twitter/Form/Decorator')
            ->setDefaultDisplayGroupClass('Sch_Twitter_Form_DisplayGroup');
        return $this;
    }

    /**
     * @param bool $overwrite
     * @return Sch_Twitter_Form_Injector
     */
    public function injectDecorators($overwrite = true)
    {
        $form = $this->getForm();
        if ($overwrite) {
            $form->clearDecorators();
        }
        $form->addDecorator('FormElements');
        if ($form->getLegend()) {
            $form->addDecorator('Fieldset');
        }
        $form->addDecorator('Form');
        return $this;
    }

    /**
     * @return \Sch_Twitter_Form_Injector
     */
    public function injectMarkup()
    {
        $form = $this->getForm();
        if (($type = $this->getType()) && array_key_exists($type, $this->_types)) {
            foreach ($this->_types[$type] as $get => $params) {
                $method = 'set' . ucfirst($get);
                if (method_exists($form, $method)) {
                    call_user_func(array($form, $method), $params);
                }
                if (method_exists($form, $get)) {
                    call_user_func(array($form, $get), $params);
                }
            }
        }
        return $this;
    }

    /**
     * @return \Sch_Twitter_Form_Injector
     */
    public function injectElements()
    {
        $form = $this->getForm();
        foreach ($form->getElements() as /** @var $element Zend_Form_Element */$element) {
            $injector = new Sch_Twitter_Form_Element_Injector($element);
            $injector->injectDecorators();
        }
        return $this;
    }

    /**
     * @param $type
     * @return Sch_Twitter_Form_Injector
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param \Zend_Form $form
     * @return self
     */
    public function setForm($form)
    {
        $this->_form = $form;
        return $this;
    }

    /**
     * @return \Zend_Form
     */
    public function getForm()
    {
        return $this->_form;
    }

}
