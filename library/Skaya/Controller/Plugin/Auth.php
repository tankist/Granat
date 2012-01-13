<?php
class Skaya_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * Auth provider
     *
     * @var Zend_Auth
     */
    protected $_auth = null;

    /**
     * Acl provider
     *
     * @var Zend_Acl
     */
    protected $_acl = array();

    protected $_noauth = array();

    protected $_noacl = array();

    protected $_isSeparateAuthNamespace = false;

    /**
     * @param Zend_Auth $auth
     * @param Zend_Acl $acl
     * @param string $module
     * @param bool $isSeparateAuthNamespace
     */
    public function __construct(Zend_Auth $auth, Zend_Acl $acl, $module = 'default', $isSeparateAuthNamespace = false)
    {
        $this
            ->setAuth($auth)
            ->setAcl($acl, $module)
            ->setIsSeparateAuthNamespace($isSeparateAuthNamespace);

        $defaultModuleName = Zend_Controller_Front::getInstance()->getDefaultModule();
        if ($module != $defaultModuleName) {
            $this->setNoAuthRules(array(
                'module' => 'default',
                'controller' => 'login',
                'action' => 'index'
            ), $defaultModuleName);

            $this->setNoAclRules(array(
                'module' => 'default',
                'controller' => 'error',
                'action' => 'privileges'
            ), $defaultModuleName);
        }
    }

    /**
     * @param Zend_Auth $auth
     * @return Skaya_Controller_Plugin_Auth
     */
    public function setAuth(Zend_Auth $auth)
    {
        $this->_auth = $auth;
        return $this;
    }

    /**
     * @return null|Zend_Auth
     */
    public function getAuth()
    {
        if ($this->_isSeparateAuthNamespace && $this->_auth instanceof Zend_Auth) {
            $module = $this->getRequest()->getModuleName();
            if (empty($module)) {
                $module = Zend_Controller_Front::getInstance()->getDefaultModule();
            }
            $this->_auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_' . ucfirst($module)));
        }
        return $this->_auth;
    }

    /**
     * @param Zend_Acl $acl
     * @param string $module
     * @return Skaya_Controller_Plugin_Auth
     */
    public function setAcl(Zend_Acl $acl, $module = 'default')
    {
        $this->_acl[$module] = $acl;
        return $this;
    }

    /**
     * @param string $module
     * @return array|bool|Zend_Acl
     */
    public function getAcl($module = 'default')
    {
        return (array_key_exists($module, $this->_acl)) ? $this->_acl[$module] : false;
    }

    /**
     * @param array $noAcl
     * @param string $module
     * @return Skaya_Controller_Plugin_Auth
     */
    public function setNoAclRules(array $noAcl, $module = 'default')
    {
        $this->_noacl[$module] = $noAcl;
        return $this;
    }

    /**
     * @param string $module
     * @return array|bool
     */
    public function getNoAclRules($module = 'default')
    {
        return (array_key_exists($module, $this->_noacl)) ? $this->_noacl[$module] : false;
    }

    /**
     * @param array $noAuth
     * @param string $module
     * @return Skaya_Controller_Plugin_Auth
     */
    public function setNoAuthRules(array $noAuth, $module = 'default')
    {
        $this->_noauth[$module] = $noAuth;
        return $this;
    }

    /**
     * @param string $module
     * @return array|bool
     */
    public function getNoAuthRules($module = 'default')
    {
        return (array_key_exists($module, $this->_noauth)) ? $this->_noauth[$module] : false;
    }

    /**
     * @param  $isSeparateAuthNamespace
     * @return Skaya_Controller_Plugin_Auth
     */
    public function setIsSeparateAuthNamespace($isSeparateAuthNamespace)
    {
        $this->_isSeparateAuthNamespace = $isSeparateAuthNamespace;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSeparateAuthNamespace()
    {
        return $this->_isSeparateAuthNamespace;
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return bool
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $module = (is_string($request->getModuleName())) ? strtolower($request->getModuleName()) : 'default';

        $auth = $this->getAuth();
        $acl = $this->getAcl($module);

        if (!$auth || !$acl) {
            return false;
        }

        $role = \Entities\User::USER_ROLE_GUEST;
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            if (is_object($identity) && method_exists($identity, 'getRole')) {
                $role = $identity->getRole();
            }
            if (is_array($identity)) {
                $role = $identity['role'];
            }
            if (!$acl->hasRole($role)) {
                $role = \Entities\User::USER_ROLE_GUEST;
            }
        }
        $resource = $controller;

        if (!$acl->has($resource)) {
            $resource = null;
        }

        if (!$acl->isAllowed($role, $resource, $action)) {
            $redirectType = (!$auth->hasIdentity()) ? 'noauth' : 'noacl';
            $this->_setRequestParameters($redirectType);
            $request->setDispatched(false);
        }

        if (isset($identity)) {
            $request->setParam('__user', $identity);
        }
        return true;
    }

    /**
     * @throws Zend_Controller_Exception
     * @param  $noType
     * @return Skaya_Controller_Plugin_Auth
     */
    protected function _setRequestParameters($noType)
    {
        $moduleName = strtolower($this->getRequest()->getModuleName());
        $noType = '_' . strtolower($noType);
        if (property_exists($this, $noType)) {
            $noRules = $this->$noType;
            if (!array_key_exists($moduleName, $noRules)) {
                $moduleName = Zend_Controller_Front::getInstance()->getDefaultModule();
                if (!array_key_exists($moduleName, $noRules)) {
                    throw new Zend_Controller_Exception('Unknown redirect params for the current module');
                }
            }
            $noRules = $noRules[$moduleName];
            foreach ($noRules as $actionName => $rule) {
                $actionName = "set" . ucwords($actionName) . "Name";
                if (method_exists($this->getRequest(), $actionName)) {
                    call_user_func(array($this->getRequest(), $actionName), $rule);
                }
            }
        }
        else {
            throw new Zend_Controller_Exception('Wrong redirect type provided. Only NoAcl and NoAuth are supported');
        }
        return $this;
    }
}

?>
