<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

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
	
	protected function _initResourceAutoloader() {
		$loader = new Skaya_Loader_Autoloader_Resource(array(
			'namespace' => '',
			'basePath' => APPLICATION_PATH,
		));
		$loader->addResourceTypes(array(
			'dbtable' => array(
				'namespace' => 'Model\\DbTable',
				'path'      => 'models/DbTable',
			),
			'mappers' => array(
				'namespace' => 'Model\\Mapper',
				'path'      => 'models/mappers',
			),
			'viewhelper' => array(
				'namespace' => 'View\\Helper',
				'path'      => 'views/helpers',
			),
			'viewfilter' => array(
				'namespace' => 'View\\Filter',
				'path'      => 'views/filters',
			),
			'form'    => array(
				'namespace' => 'Form',
				'path'      => 'forms',
			),
			'model'   => array(
				'namespace' => 'Model',
				'path'      => 'models',
			),
			'plugin'  => array(
				'namespace' => 'Plugin',
				'path'      => 'plugins',
			),
			'service' => array(
				'namespace' => 'Service',
				'path'      => 'services',
			),
		));
		$loader->setDefaultResourceType('model');
		return $loader;
	}

}