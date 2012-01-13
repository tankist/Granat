<?php

class Sch_Controller_Action_Helper_CurrentUser extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var \Entities\User
     */
    protected $_currentUser;

    /**
     * @var stdClass
     */
    protected $_identity;

    public function getCurrentUser()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (!$this->_currentUser or $identity != $this->_identity) {
            $this->_identity = $identity;
            /** @var $emHelper Sch_Controller_Action_Helper_Em */
            if (!($emHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Em'))) {
                throw new Zend_Controller_Action_Exception('Entity manager cannot be found');
            }
            $manager = new Service_User($emHelper->getEntityManager());
            $user = !empty($identity) ? $manager->getByEmail($identity) : null;
            $this->_currentUser = $user;
            if ($user) {
                $user->setOnlineLast(new DateTime());
                $manager->save($user);
            }
            $this->getFrontController()->setParam('user', $this->_currentUser);
        }
        return $this->_currentUser;
    }

    public function direct()
    {
        return $this->getCurrentUser();
    }

    public function preDispatch()
    {
        Zend_Layout::getMvcInstance()->getView()->currentUser = $this->getCurrentUser();
    }

}
