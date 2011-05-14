<?php
class Skaya_Controller_Plugin_Resource extends Zend_Controller_Plugin_Abstract {
	
	/**
	* @var Zend_Application_Bootstrap_Bootstrap
	*/
	protected $_bootstrap = null;
	
	protected $_front = null;
	
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
		$this->_bootstrap();
	}
	
	protected function _getFrontController() {
		if (!$this->_front) {
			$this->_front = Zend_Controller_Front::getInstance();
		}
		return $this->_front;
	}
	
	protected function _getBootstrap() {
		if (!$this->_bootstrap) {
			$front = $this->_getFrontController();
			$this->_bootstrap = $bootstrap = $front->getParam('bootstrap');
			$module = $this->getRequest()->getModuleName();
			if ($module && $module != $front->getDefaultModule()) {
				$this->_bootstrap = $bootstrap->modules[$module];
			}
		}
		return $this->_bootstrap;
	}
	
	protected function _setBootstrap(Zend_Application_Bootstrap_Bootstrap $bootstrap) {
		if ($module = $this->getRequest()->getModuleName()) {
			$_bootstrap = $this->_getFrontController()->getParam('bootstrap');
			$_bootstrap->modules[$module] = $bootstrap;
			$bootstrap = $_bootstrap;
		}
		$this->_getFrontController()->setParam('bootstrap', $bootstrap);
	}
	
	protected function _getOptions() {
		$options = array();
		$bootstrap = $this->_getBootstrap();
		if ($bootstrap) {
			$bootstrapOptions = $bootstrap->getOptions();
			if (isset($bootstrapOptions['plugin'])) {
				if (isset($bootstrapOptions['plugin']['resource'])) {
					$options = $bootstrapOptions['plugin']['resource'];
				}
			}
		}
		return $options;
	}
	
	protected function _bootstrap() {
		$bootstrap = $this->_getBootstrap();
		$pluginOptions = $this->_getOptions();
		if (empty($pluginOptions['resources'])) {
			return;
		}
		$bootstrap->setOptions($pluginOptions);
		foreach ($pluginOptions['resources'] as $resourceName => $resourceOptions) {
			$bootstrap->bootstrap($resourceName);
		}
		$this->_setBootstrap($bootstrap);
	}
	
}
?>
