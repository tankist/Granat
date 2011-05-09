<?php

class Admin_FabricsController extends Zend_Controller_Action {

	const ITEMS_PER_PAGE = 20;

	/**
	 * @var Model_User
	 */
	protected $_user;

	public function init() {
		$this->_helper->getHelper('AjaxContext')->initContext('json');
		$this->_user = $this->_helper->user();
	}

	public function indexAction() {
		$request = $this->getRequest();
		$page = $request->getParam('page', 1);
		$this->view->order = $order = $request->getParam('order');
		$this->view->orderType = $orderType = $request->getParam('orderType', 'ASC');
		/**
		 * @var Skaya_Paginator $fabricsPaginator
		 */
		$orderString = null;
		if ($order) {
			$orderString = $order . ' ' . $orderType;
		}

		$fabricsPaginator = $this->_helper->service('Fabric')->getFabricsPaginator($orderString);

		$this->view->paginator = $fabricsPaginator;
		$fabricsPaginator->setCurrentPageNumber($page)->setItemCountPerPage(self::ITEMS_PER_PAGE);
		$this->view->fabrics = $fabricsPaginator->getCurrentItems();
		$this->view->page = $page;
	}

	public function addAction() {
		$form = new Admin_Form_Fabric(array(
			'name' => 'user',
			'action' => $this->_helper->url('save'),
			'method' => Zend_Form::METHOD_POST
		));

		$sessionData = $this->_helper->sessionSaver('fabricData');
		if ($sessionData) {
			$form->populate($sessionData);
			$this->_helper->sessionSaver->delete('fabricData');
		}

		$form->removeElement('id');
		$form->prepareDecorators();
		$this->view->form = $form;
	}

	public function editAction() {
		$fabric_id = $this->_getParam('id');
		$fabric = $this->_helper->service('Fabric')->getFabricById($fabric_id);
		if ($fabric->isEmpty()) {
			throw new Zend_Controller_Action_Exception('Fabric not found', 404);
		}

		$form = new Admin_Form_Fabric(array(
			'name' => 'fabric',
			'action' => $this->_helper->url('save'),
			'method' => Zend_Form::METHOD_POST
		));
		$data = $fabric->toArray();

		$sessionData = $this->_helper->sessionSaver('fabricData');
		if ($sessionData) {
			$data = $sessionData;
			$this->_helper->sessionSaver->delete('fabricData');
		}

		$form->populate($data);
		$form->prepareDecorators();
		$this->view->form = $form;
	}

	public function saveAction() {
		$request = $this->getRequest();
		$fabric_id = $request->getParam('id');
		if (!empty($fabric_id)) {
			$fabric = $this->_helper->service('Fabric')->getFabricById($fabric_id);
			if ($fabric->isEmpty()) {
				throw new Zend_Controller_Action_Exception('Fabric not found', 404);
			}
		}
		else {
			$fabric = $this->_helper->service('Fabric')->create();
		}

		$form = new Admin_Form_Fabric(array(
			'name' => 'fabric'
		));

		if ($request->isPost() && $form->isValid($request->getPost())) {
			$data = $form->getValues();
			$fabric->populate($data);
			$fabric->save();
			$this->_helper->flashMessenger->addMessage(array('message' => 'Fabric saved Successfully', 'status' => 'success'));
			$this->_redirect($this->_helper->url(''));
		}
		else {
			$this->_helper->flashMessenger->addErrorsFromForm($form);
			$data = $form->getValues();
			$this->_helper->sessionSaver('fabricData', $data);
			if (!empty($fabric_id)) {
				$this->_redirect($this->_helper->url('edit', null, null, array('id' => $fabric_id)));
			}
			else {
				$this->_redirect($this->_helper->url('add'));
			}
		}
	}

	public function deleteAction() {
		$fabric_id = $this->_getParam('id');
		$fabric = $this->_helper->service('Fabric')->getFabricById($fabric_id);
		if ($fabric->isEmpty()) {
			throw new Zend_Controller_Action_Exception('Fabric ID NOT Found', 404);
		}
		$fabric->delete();
		$this->_redirect($this->_helper->url(''));
	}
	
}