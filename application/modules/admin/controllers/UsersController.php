<?php

class Admin_UsersController extends Zend_Controller_Action {

	/**
	 * @var Model_User
	 */
	protected $_user;

	public function init() {
		$this->_helper->getHelper('AjaxContext')->initContext('json');
		$this->_user = $this->_helper->user();
	}

	public function indexAction() {
		// action body
	}

	public function loginAction() {
		$request = $this->getRequest();

		$loginForm = new Admin_Form_Login(array(
			'name' => 'loginForm',
			'class' => 'login_box',
			'action' => $this->_helper->url('login')
		));

		$notEmptyValidator = new Zend_Validate_NotEmpty();
		$notEmptyValidator->setMessage('Email & Password are Required', Zend_Validate_NotEmpty::IS_EMPTY);

		$loginForm->email->addValidator($notEmptyValidator, true);
		$loginForm->password->addValidator($notEmptyValidator, true);

		if ($request->isPost()) {
			if ($loginForm->isValid($request->getPost())) {
				$user = Service_User::create(array(
					'email' => $loginForm->email->getValue(),
					'password' => $loginForm->password->getValue()
				));
				$auth = Zend_Auth::getInstance();
				$authResult = $auth->authenticate($user);
				if ($authResult->isValid()) {
					$user->lastLoginDate = time();
					$user->save();
					if (!$request->isXmlHttpRequest()) {
						$referer = $loginForm->referer->getValue();
						$this->_redirect(($referer) ? $referer : '/' . $request->getModuleName());
					}
				}
				else {
					$this->view->error = array(
						'form' => $authResult->getMessages()
					);
				}
			}
			else {
				$errors = $loginForm->getMessages();
				if (isset($errors['email']['isEmpty']) && isset($errors['password']['isEmpty'])) {
					unset($errors['password']['isEmpty']);
					if (count($errors['password']) == 0) {
						unset($errors['password']);
					}
				}
				$this->view->error = $errors;
			}
		}
		else {
			Zend_Layout::getMvcInstance()->setLayout('login_layout', true);
			$forgotForm = new Admin_Form_ForgotPassword(array(
				'name' => 'forgotForm',
				'action' => $this->_helper->url('forget')
			));

			$loginForm->referer->setValue($request->getRequestUri());

			$this->view->loginForm = $loginForm->prepareDecorators();
			$this->view->forgotForm = $forgotForm->prepareDecorators();
		}
	}

	public function logoutAction() {
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('/admin');
	}


}