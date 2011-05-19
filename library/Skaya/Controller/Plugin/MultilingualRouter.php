<?php
class Skaya_Controller_Plugin_MultilingualRouter extends Zend_Controller_Plugin_Abstract {

	protected $_defaultLanguage;

	public function __construct($defaultLanguage) {
		$this->setDefaultLanguage($defaultLanguage);
	}

	public function routeStartup(Zend_Controller_Request_Http $request) {
		$uri = $request->getRequestUri();
		if (!is_int(stripos($uri, '/ru')) && !is_int(stripos($uri, '/en'))) {
			$request->setRequestUri('/' . $this->getDefaultLanguage() . $uri);
			$request->setParam('lang', $this->getDefaultLanguage());
		}
	}

	public function setDefaultLanguage($defaultLanguage) {
		$this->_defaultLanguage = $defaultLanguage;
		return $this;
	}

	public function getDefaultLanguage() {
		return $this->_defaultLanguage;
	}

}