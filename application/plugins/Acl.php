<?php

class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    protected $_defaultRole = 0;

    /**
     *
     * @var Zend_Acl
     */
    protected $_acl;

    protected $_aclRoute = '%s.%s';

    /**
     * Location to go to if the user isn't authenticated
     * @var array
     */
    protected $_noAuth = array(
        'module' => 'users',
        'controller' => 'auth',
        'action' => 'login',
    );

    /**
     * Location to go to if the user isn't authorized
     *
     * @var array
     */
    protected $_noAcl = array(
        'module' => 'default',
        'controller' => 'error',
        'action' => 'denied',
    );

    public function getUser()
    {
        return Zend_Controller_Action_HelperBroker::getExistingHelper('currentUser')->getCurrentUser();
    }

    public function isRegistered()
    {
        return (null != $this->getUser());
    }

    public function getRole()
    {
        if ($user = $this->getUser()) {
            return $user->role;
        }
        return $this->_defaultRole;
    }

    public function isAllowed($resource = null, $privelege = null)
    {
        return $this->_acl->isAllowed($this->getRole(), $resource, $privelege);
    }

    public function direct()
    {
        return $this->getUser();
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_acl = $this->_getAcl();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $module = $request->getModuleName();

        $resource = sprintf($this->_aclRoute, $module, $controller);
        if (!$this->_acl->has($resource)) {
            $resource = $controller;
        }
        if (!$this->_acl->has($resource)) {
            $resource = null;
        }
        if ($this->isAllowed($resource, $action)) {
            $user = $this->getUser();
            return;
        }

        // Auth fail

        $returnUrl = urlencode($request->getRequestUri());
        $request->setParam('return', $returnUrl);

        if ($this->isRegistered()) {
            $module = $this->_noAcl['module'];
            $controller = $this->_noAcl['controller'];
            $action = $this->_noAcl['action'];
        } else {
            $module = $this->_noAuth['module'];
            $controller = $this->_noAuth['controller'];
            $action = $this->_noAuth['action'];
        }

        $request
            ->setModuleName($module)
            ->setControllerName($controller)
            ->setActionName($action)
            ->setDispatched(false);
    }

    protected function _getAcl()
    {
        if (Zend_Registry::isRegistered('acl')) {
            return Zend_Registry::get('acl');
        }
        include APPLICATION_PATH . '/configs/acl.php';
        if (!isset($acl) || !($acl instanceof Zend_Acl)) {
            throw new RuntimeException('Can not load ACL object.');
        }
        Zend_Registry::set('acl', $acl);
        return $acl;
    }

}
