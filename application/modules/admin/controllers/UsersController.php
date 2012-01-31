<?php

/**
 * @class Admin_UsersController
 */
class Admin_UsersController extends Zend_Controller_Action
{

    /**
     * @var Service_User
     */
    protected $_service = null;

    public function init()
    {
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('login');
        $this->_service = new Service_User($this->_helper->Em());
    }

    public function indexAction()
    {
        $this->_forward('login');
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $loginForm = new Admin_Form_Login();
        if ($request->isPost()) {
            if ($loginForm->isValid($request->getPost())) {
                $data = $loginForm->getValues();
                if ($this->_authenticate($data)) {
                    /** @var $me \Entities\User */
                    if (($me = $this->_helper->currentUser())) {
                        $me->isOnline(true);
                        $this->_service->save($me);
                    }
                    $this->_redirect($this->_getParam('return', $this->_helper->url('', '')));
                }
            }
        }
        $loginForm->setAction($this->_helper->url('login'));
        $this->view->form = $loginForm->prepareDecorators();
    }

    public function logoutAction()
    {
        /** @var $me \Entities\User */
        if (($me = $this->_helper->currentUser())) {
            $me->isOnline(false);
            $this->_service->save($me);
        }
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->_redirect($this->_helper->url('login'));
    }

    private function _authenticate($data)
    {
        if (empty($data['email']) || empty($data['password'])) {
            return false;
        }

        if (!empty($data['is_remember']) && $data['is_remember']) {
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
        $authAdapter->setIdentity($data['email']);
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
