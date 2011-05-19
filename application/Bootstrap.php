<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	protected function _initModule() {
		$loader = new Zend_Application_Module_Autoloader(array(
			'namespace' => '',
		    'basePath' => APPLICATION_PATH,
		));
		return $loader;
	}

	protected function _initAutoloadNamespace() {
		$this->getApplication()->getAutoloader()->registerNamespace(array('Skaya_'));
	}

	protected function _initSessionNamespace() {
		$session = new Zend_Session_Namespace('Skaya');
		return $session;
	}

	protected function _initAuthAcl() {
		$this->bootstrap('acl');

		$front = Zend_Controller_Front::getInstance();
		$authPlugin = $front->getPlugin('Skaya_Controller_Plugin_Auth');
		if (!$authPlugin) {
			$authPlugin = new Skaya_Controller_Plugin_Auth(
				Zend_Auth::getInstance(),
				$this->getResource('acl')
			);
			$front->registerPlugin($authPlugin);
		}
		else {
			$authPlugin
				->setAcl($this->getResource('acl'))
				->setAuth(Zend_Auth::getInstance());
		}
		$authPlugin->setIsSeparateAuthNamespace(true);
		$options = $this->getOption('authacl');
		foreach ((array)$options as $key => $value) {
			if (strtolower($key) == 'noauth') {
				$authPlugin->setNoAuthRules($value);
			}
			if (strtolower($key) == 'noacl') {
				$authPlugin->setNoAclRules($value);
			}
		}
		return $authPlugin;
	}

	protected function _initDbCharset() {
		$this->bootstrap('db');
		$db = $this->getResource('db');
		if ($db instanceof Zend_Db_Adapter_Abstract) {
			$db->exec('SET NAMES utf8');
		}
	}

	protected function _initRoutes() {
		$this->bootstrap('frontcontroller')->bootstrap('locale');
		/**
		 * @var Zend_Controller_Front $front
		 */
		$front = $this->getResource('frontcontroller');
		$routes = new Zend_Config_Ini(APPLICATION_PATH . '/configs/router.ini', APPLICATION_ENV);
		/**
		 * @var Zend_Controller_Router_Rewrite $router
		 */
		$router = $front->getRouter();
		$router->addConfig($routes, 'routes');
		$routes = $router->getRoutes();

		$langRoute = $router->getRoute('language');

		foreach ($routes as $name => $route) {
			if (!in_array($name, array('language', 'default', 'defaultmodule'))) {
				$router->addRoute($name, $langRoute->chain($route));
			}
		}

		$front->registerPlugin(new Skaya_Controller_Plugin_MultilingualRouter('ru'));
	}

	protected function _initYandexMaps() {
		$this->bootstrap('frontcontroller');
		$options = $this->getOption('ymaps');
		$this->getResource('frontcontroller')->setParam('ymaps', $options);
	}

	protected function _initTranslation() {
		Zend_Controller_Action_HelperBroker::addHelper(new Skaya_Controller_Action_Helper_Translator());
	}

}