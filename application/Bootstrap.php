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
		$routes = new Zend_Config_Ini(APPLICATION_PATH . '/configs/router.ini', APPLICATION_ENV);
		Zend_Controller_Front::getInstance()->getRouter()->addConfig($routes, 'routes');
	}

}