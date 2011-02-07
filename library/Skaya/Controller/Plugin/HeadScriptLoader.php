<?php
class Skaya_Controller_Plugin_HeadScriptLoader extends Zend_Controller_Plugin_Abstract {
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$moduleName = $request->getModuleName();
		if ($request->isXmlHttpRequest()) {
			return;
		}
		
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$view = $bootstrap->view;
		if (!empty($moduleName) && isset($bootstrap->modules[$moduleName]->view)) {
			$view = $bootstrap->modules[$moduleName]->view;
		}
		if (!$view) {
			return false;
		}
		
		$options = $bootstrap->getOptions();
		
		$applicationConfig = (isset($options['plugin']['headscript']))?$options['plugin']['headscript']:array();
		$moduleConfig = (isset($options[$moduleName]['plugin']['headscript']))?$options[$moduleName]['plugin']['headscript']:array();
		$headScriptConfig = array_unique(array_merge($applicationConfig, $moduleConfig));
		
		$helper = $view->getHelper('headScript');
		foreach ($headScriptConfig as $key => $value) {
			if (is_string($value)) {
				$helper->appendFile($value);
			}
			if (is_array($value)) {
				$attributes = $params = array();
				foreach ($value as $_name => $_v) {
					switch ($_name) {
						case "src":
							$params[0] = (string)$_v;
							break;
						case "type":
							$params[1] = (string)$_v;
							break;
						case "condition":
							$attributes['conditional'] = (string)$_v;
							break;
						case "attributes":
						default:
							$attributes = array_merge($attributes, (array)$_v);
							break;
					}
				}
				if (!empty($attributes)) {
					$params[3] = (array)$attributes;
				}
				$helperMethodName = 'appendFile';
				if (is_string($key)) {
					array_unshift($params, $key);
					$helperMethodName = 'offsetSetFile';
				}
				call_user_func_array(array($helper, $helperMethodName), $params);
			}
			
		}
	}
}
?>