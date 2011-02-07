<?php
class Skaya_Controller_Action_Helper_MailSender extends Zend_Controller_Action_Helper_Abstract {
	
	public function __call($methodName, $params) {
		if (strpos($methodName, 'email') !== 0) {
			throw new Zend_Controller_Action_Exception('Method ' . $methodName . ' was not found in this helper');
		}
		
		$methodPart = substr($methodName, 5);
		list($from, $to, $subject, $helperParams) = $params;
		
		$scriptBasePath = (!empty($helperParams['scriptBasePath']))?
								rtrim($helperParams['scriptBasePath'], '/\\'):
								$this->getRequest()->getControllerName() . '/';
		$viewScript = (!empty($helperParams['viewScript']))?
								$helperParams['viewScript']:
								'email_' . strtolower(Zend_Filter::filterStatic($methodPart, 'Word_CamelCaseToUnderscore'));
		
		$view = clone $this->getActionController()->view;
		$mailer = new Zend_Mail('UTF-8');
		list($fromName, $fromEmail) = each($from);
		$mailer->setFrom($fromEmail,$fromName)->addTo($to)->setSubject($subject);
		$mailer->setBodyHtml($view->render($scriptBasePath . $viewScript . '.' . $this->getActionController()->viewSuffix));
		$mailer->send();
	}
	
}
?>
