<?php

class Admin_ErrorController extends Zend_Controller_Action
{

	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');
		
		if (!$errors) {
			$this->view->message = 'You have arrived to an ERROR';
			return;
		}
		
		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$priority = Zend_Log::NOTICE;
				$this->view->message = 'Page NOT Found';
				break;
			default:
				// application error
				$this->getResponse()->setHttpResponseCode(500);
				$priority = Zend_Log::CRIT;
				$this->view->message = 'Application error';
				break;
		}
		
		// Log exception, if logger available
		/**
		 * @var Zend_Log $log
		 */
		if ($log = $this->getLog()) {
			$log->log($this->view->message, $priority, $errors->exception->getMessage());
			$log->info('Trace');
			$log->debug($errors->exception->getTrace());
		}
		
		// conditionally display exceptions
		if ($this->getInvokeArg('displayExceptions') == true) {
			$this->view->exception = $errors->exception;
		}
		
		$this->view->request   = $errors->request;
	}

	public function getLog()
	{
		$bootstrap = $this->getInvokeArg('bootstrap');
		if (!$bootstrap->hasResource('Log')) {
			return false;
		}
		$log = $bootstrap->getResource('Log');
		return $log;
	}


}

