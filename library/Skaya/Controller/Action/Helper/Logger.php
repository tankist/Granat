<?php
class Skaya_Controller_Action_Helper_Logger extends Zend_Controller_Action_Helper_Abstract
{

    protected $_bootstrap;

    public function getBootstrap()
    {
        if (!$this->_bootstrap) {
            $this->_bootstrap = $this->getFrontController()->getParam('bootstrap');
        }
        return $this->_bootstrap;
    }

    public function direct($value)
    {
        if (!($logger = $this->getBootstrap()->getResource('log'))) {
            return false;
        }
        if (is_scalar($value)) {
            $logger->info($value);
        }
        else {
            $logger->debug($value);
        }
        return true;
    }

}
