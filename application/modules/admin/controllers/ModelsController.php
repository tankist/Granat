<?php

class Admin_ModelsController extends Zend_Controller_Action {

	const ITEMS_PER_PAGE = 20;

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
		 * @var Skaya_Paginator $modelsPaginator
		 */
		$orderString = null;
		if ($order) {
			$orderString = $order . ' ' . $orderType;
		}

		$modelsPaginator = $this->_helper->service('Model')->getModelsPaginator($orderString);

		$this->view->paginator = $modelsPaginator;
		$modelsPaginator->setCurrentPageNumber($page)->setItemCountPerPage(self::ITEMS_PER_PAGE);
		$this->view->models = $modelsPaginator->getCurrentItems();
		$this->view->page = $page;
	}

	public function addAction() {
		$collections = $this->_helper->service('Collection')->getCollections('name');
		$filter = new Skaya_Filter_Array_Map('name', 'id');

		$form = new Admin_Form_Model(array(
			'name' => 'user',
			'action' => $this->_helper->url('save'),
			'method' => Zend_Form::METHOD_POST,
			'collections' => $filter->filter($collections->toArray())
		));

		$sessionData = $this->_helper->sessionSaver('modelData');
		if ($sessionData) {
			$form->populate($sessionData);
			$this->_helper->sessionSaver->delete('modelData');
		}

		$form->removeElement('id');
		$form->prepareDecorators();
		$this->view->form = $form;
	}

	public function editAction() {
		$model_id = $this->_getParam('id');
		$model = $this->_helper->service('Model')->getModelById($model_id);
		if ($model->isEmpty()) {
			throw new Zend_Controller_Action_Exception('Model not found', 404);
		}

		$form = new Admin_Form_Model(array(
			'name' => 'model',
			'action' => $this->_helper->url('save'),
			'method' => Zend_Form::METHOD_POST
		));
		$data = $model->toArray();

		$sessionData = $this->_helper->sessionSaver('modelData');
		if ($sessionData) {
			$data = $sessionData;
			$this->_helper->sessionSaver->delete('modelData');
		}

		$form->populate($data);
		$form->prepareDecorators();
		$this->view->form = $form;
	}

	public function saveAction() {
		$request = $this->getRequest();
		$model_id = $request->getParam('id');
		if (!empty($model_id)) {
			$model = $this->_helper->service('Model')->getModelById($model_id);
			if ($model->isEmpty()) {
				throw new Zend_Controller_Action_Exception('Model not found', 404);
			}
		}
		else {
			$model = $this->_helper->service('Model')->create();
		}

		$form = new Admin_Form_Model(array(
			'name' => 'model'
		));

		if ($request->isPost() && $form->isValid($request->getPost())) {
			$data = $form->getValues();
			$model->populate($data);
			$model->save();
			$this->_helper->flashMessenger->addMessage(array('message' => 'Model saved Successfully', 'status' => 'success'));
			$this->_redirect($this->_helper->url(''));
		}
		else {
			$this->_helper->flashMessenger->addErrorsFromForm($form);
			$data = $form->getValues();
			$this->_helper->sessionSaver('modelData', $data);
			if (!empty($model_id)) {
				$this->_redirect($this->_helper->url('edit', null, null, array('id' => $model_id)));
			}
			else {
				$this->_redirect($this->_helper->url('add'));
			}
		}
	}

	public function deleteAction() {
		$model_id = $this->_getParam('id');
		$model = $this->_helper->service('Model')->getModelById($model_id);
		if ($model->isEmpty()) {
			throw new Zend_Controller_Action_Exception('Model ID NOT Found', 404);
		}
		$model->delete();
		$this->_redirect($this->_helper->url(''));
	}

}