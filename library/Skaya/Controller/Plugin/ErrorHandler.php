<?php
class Skaya_Controller_Plugin_ErrorHandler extends Zend_Controller_Plugin_ErrorHandler {
	protected function _handleError(Zend_Controller_Request_Abstract $request) {
		if ($this->getErrorHandlerModule() == $request->getModuleName()) {
			Zend_Controller_Front::getInstance()->setParam('noErrorHandler', false);
			parent::_handleError($request);
			Zend_Controller_Front::getInstance()->setParam('noErrorHandler', true);
		}
	}
}
?>
