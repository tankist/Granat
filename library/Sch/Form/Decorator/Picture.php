<?php
class Sch_Form_Decorator_Picture extends Zend_Form_Decorator_Abstract
    implements Zend_Form_Decorator_Marker_File_Interface
{
    public function buildLabel()
    {
        $element = $this->getElement();
        $helper = $element->helper;
        $label = $element->getLabel();
        switch ($helper) {
            default:
                if ($element->isRequired()) {
                    $label .= '<span class="required">*</span>';
                }
                $label .= ':';
                return $element->getView()->formLabel($element->getName(), $label, array('escape' => false));
                break;
            case 'formFile':
                if ($element->isRequired()) {
                    $label .= '<span class="required">*</span>';
                }
                return $element->getView()->formLabel($element->getName(), $label, array('escape' => false));
                break;
            case 'formHidden':
                break;
            case 'formCheckbox':
                return $label;
                break;
        }
    }

    public function buildInput()
    {
        $element = $this->getElement();
        $helper = $element->helper;
        switch ($helper) {
            default:
            case 'formText':
            case 'formTextarea':
            case 'formPassword':
            case 'formHidden':
            case 'formImage':
                $result = $element->getView()->$helper(
                    $element->getName(),
                    $element->getValue(),
                    $element->getAttribs()
                );
                break;
            case 'formFile':
                $result = "";
                $file = new Sch_Form_Decorator_File();
                $result = $file->setElement($element)->render("");
                break;
            case 'formRadio':
            case 'formCheckbox':
            case 'formSelect':
                $result = $element->getView()->$helper(
                    $element->getName(),
                    $element->getValue(),
                    $element->getAttribs(),
                    $element->options
                );
                break;
        }

        return $result;
    }

    public function buildErrors()
    {
        $element = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '<div id="' . $element->getName() . '-error" class="errors"></div>';
        }
        return '<div id="' . $element->getName() . '-error" class="errors">' .
            $element->getView()->formErrors($messages) . '</div>';
    }

    public function buildDescription()
    {
        $element = $this->getElement();
        $desc = $element->getDescription();
        if (empty($desc)) {
            return '';
        }
        return '<div class="description">' . $desc . '</div>';
    }

    public function render($content)
    {
        $element = $this->getElement();
        $helper = $element->helper;
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $label = $this->buildLabel();
        $input = $this->buildInput();
        $errors = $this->buildErrors();
        $desc = $this->buildDescription();

        $output = '<div class="form-element">';

        switch ($helper) {
            default:
                $output .= '<div class="label">' . $label . '</div>'
                    . '<div class="field">' . $input . '</div>';
                break;
            case 'formFile':
                $output .= '<div class="label">' . $label . '</div>';
                if ($element->getValue() && $element->getAttrib('filePath') && file_exists($element->getAttrib('rootDocumentPath') . $element->getAttrib('filePath') . $element->getValue())) {
                    $output .= '<div class="picture"><img src="/' . $element->getAttrib('filePath') . $element->getValue() . '"></div>'
                        . '<div class="field"><input type="checkbox" name="' . $element->getName() . '_delete"/> Удалить</div>';
                }
                $output .= '<div class="field">' . $input . '</div>';
                break;
        }
        $output .= $errors . $desc . '</div>';

        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}

?>
