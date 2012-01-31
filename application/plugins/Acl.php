<?php

/**
 * @class Application_Plugin_Acl
 */
class Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    /**
     * @var int
     */
    protected $_defaultRole = 0;

    /**
     *
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * @var string
     */
    protected $_aclRoute = '%s.%s';

    /**
     * Location to go to if the user isn't authenticated
     * @var array
     */
    protected $_noAuth = array(
        'module' => 'admin',
        'controller' => 'users',
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
        'action' => 'error',
    );

    /**
     * @return mixed
     */
    public function getUser()
    {
        return Zend_Controller_Action_HelperBroker::getExistingHelper('currentUser')->direct();
    }

    /**
     * @return bool
     */
    public function isRegistered()
    {
        return (null != $this->getUser());
    }

    /**
     * @return int
     */
    public function getRole()
    {
        $user = $this->getUser();
        if ($user) {
            return $user->role;
        }
        return $this->_defaultRole;
    }

    /**
     * @param null $resource
     * @param null $privelege
     * @return bool
     */
    public function isAllowed($resource = null, $privelege = null)
    {
        return $this->_acl->isAllowed($this->getRole(), $resource, $privelege);
    }

    /**
     * @return mixed
     */
    public function direct()
    {
        return $this->getUser();
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return mixed
     */
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

    /**
     * @return mixed|Zend_Acl
     * @throws RuntimeException
     */
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
