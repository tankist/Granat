<?php
class Skaya_Controller_Action_Helper_MailSender extends Zend_Controller_Action_Helper_Abstract
{

    public function __call($methodName, $params)
    {
        if (strpos($methodName, 'email') !== 0) {
            throw new Zend_Controller_Action_Exception('Method ' . $methodName . ' was not found in this helper');
        }

        $methodPart = substr($methodName, 5);
        if (count($params) < 4) {
            $params = array_pad($params, 4, false);
        }
        list($from, $to, $subject, $helperParams) = $params;

        $scriptBasePath = (is_array($helperParams) && !empty($helperParams['scriptBasePath'])) ?
            rtrim($helperParams['scriptBasePath'], '/\\') :
            $this->getRequest()->getControllerName() . '/';
        $viewScript = (is_array($helperParams) && !empty($helperParams['viewScript'])) ?
            $helperParams['viewScript'] :
            'email_' . strtolower(Zend_Filter::filterStatic($methodPart, 'Word_CamelCaseToUnderscore'));

        $view = clone $this->getActionController()->view;
        if ($view->messageUser) {
            $header = 'Good Day, ' . $view->messageUser->title . ' ' . $view->messageUser->firstName . ' ' . $view->messageUser->lastName . ' <br>';
        } else {
            $header = 'Good Day, <br>';
        }
        $footer = '<br><br><br>-----------------------------------------<br>';
        $footer .= '<b>Bharat International Support</b><br>';
        $footer .= '1/17-23 Oatley Court, Belconnen, Canberra, <br>';
        $footer .= 'ACT, 2617, Australia.<br>';
        $footer .= 'Phone No. 02 6251 0455<br>Fax. 02 62251 7509<br>';
        $footer .= 'Web. www.bharatinternational.com <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;www.bharatinternational.net <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;www.bharatinternational.com.au<br>';
        $footer .= 'Email. info@bharatinternational.net';
        $mailer = new Zend_Mail('UTF-8');
        list($fromName, $fromEmail) = each($from);
        $mailer->setFrom($fromEmail, $fromName)->addTo($to)->setSubject($subject);
        $mailer->setBodyHtml($header . $view->render($scriptBasePath . $viewScript . '.' . $this->getActionController()->viewSuffix) . $footer);
        $mailer->send();
    }

}

?>
