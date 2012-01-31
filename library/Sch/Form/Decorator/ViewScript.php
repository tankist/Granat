<?php

class Sch_Form_Decorator_ViewScript extends Zend_Form_Decorator_ViewScript
{

    /**
     * Merges given two belongsTo (array notation) strings
     *
     * @param  string $baseBelongsTo
     * @param  string $belongsTo
     * @return string
     */
    public function mergeBelongsTo($baseBelongsTo, $belongsTo)
    {
        $endOfArrayName = strpos($belongsTo, '[');

        if ($endOfArrayName === false) {
            return $baseBelongsTo . '[' . $belongsTo . ']';
        }

        $arrayName = substr($belongsTo, 0, $endOfArrayName);

        return $baseBelongsTo . '[' . $arrayName . ']' . substr($belongsTo, $endOfArrayName);
    }

    /**
     * @param $content
     * @return string
     * @throws Zend_Form_Exception
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view = $element->getView();
        if (null === $view) {
            return $content;
        }

        $viewScript = $this->getViewScript();
        if (empty($viewScript)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('No view script registered with ViewScript decorator');
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();

        $vars = $this->getOptions();
        $vars['element'] = $element;
        $vars['content'] = $content;
        $vars['decorator'] = $this;

        $this->_prepareElement();

        $viewModule = $this->getViewModule();
        if (empty($viewModule)) {
            $renderedContent = $view->partial($viewScript, $vars);
        } else {
            $renderedContent = $view->partial($viewScript, $viewModule, $vars);
        }

        // Get placement again to see if it has changed
        $placement = $this->getPlacement();

        switch ($placement) {
            case self::PREPEND:
                return $renderedContent . $separator . $content;
            case self::APPEND:
                return $content . $separator . $renderedContent;
            default:
                return $renderedContent;
        }
    }

    /**
     * @return Sch_Form_Decorator_ViewScript
     */
    protected function _prepareElement()
    {
        $element = $this->getElement();

        if ($element instanceof Zend_Form) {
            $translator = $element->getTranslator();
            $view = $element->getView();
            $belongsTo = $element->getElementsBelongTo();

            foreach ($element as /** @var $item Zend_Form_Element */$item) {
                $item->setView($view)
                    ->setTranslator($translator);
                if ($item instanceof Zend_Form_Element) {
                    $item->setBelongsTo($belongsTo);
                } elseif (!empty($belongsTo) && ($item instanceof Zend_Form)) {
                    /** @var $item Zend_Form */
                    if ($item->isArray()) {
                        $name = $this->mergeBelongsTo($belongsTo, $item->getElementsBelongTo());
                        $item->setElementsBelongTo($name, true);
                    } else {
                        $item->setElementsBelongTo($belongsTo, true);
                    }
                } elseif (!empty($belongsTo) && ($item instanceof Zend_Form_DisplayGroup)) {
                    foreach ($item as $element) {
                        $element->setBelongsTo($belongsTo);
                    }
                }
            }
        }

        return $this;
    }

}
