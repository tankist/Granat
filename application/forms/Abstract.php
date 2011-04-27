<?php
class Form_Abstract extends Zend_Form {
	
	public function prepareDecorators() {
		foreach ($this->getElements() as $elementName => $element) {
			$prepareMethodName = '_prepare' . ucfirst(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase')) . 'Decorators';
			if (method_exists($this, $prepareMethodName)) {
				call_user_func(array($this, $prepareMethodName), $element);
			}
		}
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
	
	public function populate(array $values) {
		foreach ($values as $elementName => $elementValue) {
			$populateMethodName = '_populate' . ucfirst(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase'));
			if (method_exists($this, $populateMethodName)) {
				$values = call_user_func(array($this, $populateMethodName), $values);
			}
		}

		return parent::populate($values);
	}
	
}
?>
