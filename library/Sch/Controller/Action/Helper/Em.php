<?php

class Sch_Controller_Action_Helper_Em extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Entity manager
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em = null;

    /**
     * Return entity manager
     * Could be overriden to support custom entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if ($this->_em == null) {
            /** @var $bootstrap Zend_Application_Bootstrap_Bootstrap */
            if (!($bootstrap = $this->getFrontController()->getParam('bootstrap'))) {
                throw new Zend_Controller_Action_Exception('Bootstrap not found');
            }
            /** @var $doctrine \Bisna\Doctrine\Container */
            if (!($doctrine = $bootstrap->getResource('doctrine'))) {
                throw new Zend_Controller_Action_Exception('Doctrine container not found');
            }
            $this->_em = $doctrine->getEntityManager();
            if (!($this->_em instanceof \Doctrine\ORM\EntityManager)) {
                throw new Zend_Controller_Action_Exception('Not an entity manager');
            }
        }
        return $this->_em;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function direct()
    {
        return $this->getEntityManager();
    }

}
