<?php

class Admin_UsersController extends Zend_Controller_Action
{

    /**
     * @var Service_User
     */
    protected $_manager = null;

    public function init()
    {
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('login');
        $this->_helper->getHelper('AjaxContext')->initContext('json');
        $this->_manager = new Service_User($this->_helper->Em());
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $this->view->error_login = false;

        $loginForm = new Admin_Form_Login();
        if ($request->isPost()) {
            if ($loginForm->isValid($request->getPost())) {
                $data = $loginForm->getValues();
                if ($this->_authenticate($data)) {
                    /** @var $me \Entities\User */
                    if ($me = $this->_helper->currentUser()) {
                        $me->setOnline(true)->setOnlineLast(new DateTime());
                        $this->_manager->save($me);
                    }
                    $this->_redirect($this->_getParam('back', '/'));
                }
            }
            $this->view->error_login = true;
        }
        $this->view->loginForm = $loginForm->prepareDecorators();
    }

    public function logoutAction()
    {
        /** @var $me \Entities\User */
        if ($me = $this->_helper->currentUser()) {
            $me->setOnline(false)->setOnlineLast(new DateTime());
            $this->_manager->save($me);
        }
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->_redirect('/');
    }

    private function _authenticate($data)
    {
        if (empty($data['email']) || empty($data['password'])) {
            return false;
        }

        if (!empty($data['rememberMe']) && $data['rememberMe']) {
            Zend_Session::rememberMe(86400 * 15);
        } else {
            Zend_Session::forgetMe();
        }

        $authAdapter = new Sch_Auth_Adapter_Doctrine2(
            $this->_helper->Em(),
            'Entities\User',
            'email',
            'password'
        );
        $authAdapter->setIdentity($data['login']);
        $authAdapter->setCredential(md5($data['password']));

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        if ($result->isValid()) {
            $auth->getStorage()->write($result->getIdentity());
            return true;
        }

        return false;
    }

}
