<?php
class Skaya_Rest_Route extends Zend_Rest_Route {
	
	public function match($request, $partial = false) {
		if (!$request instanceof Zend_Controller_Request_Http) {
			$request = $this->_front->getRequest();
		}

		$path = $request->getPathInfo();
		$path = trim($path, self::URI_DELIMITER);
		$pathOrig = $path;

		$result = parent::match($request, $partial);
		
		if ($pathOrig != '' || $this->_allRestful()) {
			return $result;
		}
		
		return false;
	}
	
}
?>
