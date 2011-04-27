<?php
abstract class Admin_Form_Abstract extends Zend_Form {

	public function prepareDecorators() {
		return $this;
	}

	public function setDefaults(array $values) {
		$belongsTo = $this->getElementsBelongTo();
		if (!empty($belongsTo) && array_key_exists($belongsTo, $values)) {
			$oldValues = $values;
			$values = $values[$belongsTo];
		}
		$values = $this->_setDefaults($values);
		if (!empty($belongsTo) && array_key_exists($belongsTo, $values)) {
			$oldValues[$belongsTo] = $values;
			$values = $oldValues;
		}
		return parent::setDefaults($values);
	}

	protected function _setDefaults(array $values) {
		foreach ($values as $elementName => $elementValue) {
			$populateMethodName = '_populate' . ucfirst(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase'));
			if (method_exists($this, $populateMethodName)) {
				$values = call_user_func(array($this, $populateMethodName), $values);
			}
		}
		return $values;
	}
}

?>
