<?php
class Skaya_Controller_Action_Helper_SessionSaver extends Zend_Controller_Action_Helper_Abstract {
	
	protected $_sessionNS = null;
	
	public function get($key) {
		$sessionNS = $this->_getSessionNamespace();
		if ($sessionNS === null) {
			throw new Zend_Controller_Action_Exception('Session namespace is not defined');
		}
		return $sessionNS->$key;
	}
	
	public function set($key, $value = null) {
		$sessionNS = $this->_getSessionNamespace();
		if ($sessionNS === null) {
			throw new Zend_Controller_Action_Exception('Session namespace is not defined');
		}
		$oldSessionData = $sessionData = $sessionNS->$key;
		$sessionData = $value;
		$sessionNS->$key = $sessionData;
		return $oldSessionData;
	}
	
	public function delete($key) {
		$sessionNS = $this->_getSessionNamespace();
		if ($sessionNS === null) {
			throw new Zend_Controller_Action_Exception('Session namespace is not defined');
		}
		unset($sessionNS->$key);
	}
	
	public function direct($key = null, $value = null) {
		if ($key === null) {
			return $this;
		}
		elseif ($value === null) {
			return $this->get($key);
		}
		else {
			return $this->set($key, $value);
		}
	}
	
	protected function _getSessionNamespace() {
		if ($this->_sessionNS === null) {
			$modulesResource = $this->getFrontController()->getParam('bootstrap')->modules;
			if ($modulesResource) {
				$currentModuleResource = $modulesResource[$this->getRequest()->getModuleName()];
				if ($currentModuleResource) {
					$this->_sessionNS = $currentModuleResource->sessionnamespace;
				}
			}
		}
		return $this->_sessionNS;
	}
	
}
?>
