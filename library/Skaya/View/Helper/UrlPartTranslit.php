<?php
class Skaya_View_Helper_UrlPartTranslit extends Zend_View_Helper_Abstract {

	/**
	 * @var Zend_Filter_Interface
	 */
	protected $_translitFilter = null;

	public function urlPartTranslit($text) {
		return $this->getTranslitFilter()->filter($text);
	}

	/**
	 * @param  Zend_Filter_Interface $translitFilter
	 * @return Skaya_View_Helper_Translit
	 */
	public function setTranslitFilter($translitFilter) {
		$this->_translitFilter = $translitFilter;
		return $this;
	}

	/**
	 * @return Zend_Filter_Interface
	 */
	public function getTranslitFilter() {
		if (!($this->_translitFilter instanceof Zend_Filter_Interface)) {
			$this->_translitFilter = new Zend_Filter();
			$this->_translitFilter
				->addFilter(new Zend_Filter_StringToLower())
				->addFilter(new Zend_Filter_PregReplace('$[^a-zа-я0-9]+$iu', '-'))
				->addFilter(new Skaya_Filter_Translit());
		}
		return $this->_translitFilter;
	}

}
