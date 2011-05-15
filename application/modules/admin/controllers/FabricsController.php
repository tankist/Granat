<?php

class Admin_FabricsController extends Zend_Controller_Action {

	const ITEMS_PER_PAGE = 20;

	const FABRICS_PATH = './uploads/fabrics/';

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
			'method' => Zend_Form::METHOD_POST,
			'imagePath' => realpath(self::FABRICS_PATH)
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
			'method' => Zend_Form::METHOD_POST,
			'imagePath' => realpath(self::FABRICS_PATH)
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
			'name' => 'fabric',
			'imagePath' => realpath(self::FABRICS_PATH)
		));

		if ($request->isPost() && $form->isValid($request->getPost())) {
			$data = $form->getValues();

			if (!$fabric->isEmpty()) {
				$photoFilename = $form->getImagePath() . DIRECTORY_SEPARATOR . $fabric->photo;
				if (file_exists($photoFilename) && is_file($photoFilename)) {
					/**
					 * @var Model_Photo $oldFile
					 */
					$oldFile = Service_Photo::create(array('filename' => $fabric->photo));
					@unlink($form->getImagePath() . DIRECTORY_SEPARATOR . $oldFile->getFilename());
					@unlink($form->getImagePath() . DIRECTORY_SEPARATOR . $oldFile->getFilename(Model_Photo::SIZE_FABRIC));
				}
				elseif (!isset($data['photo'])) {
					unset($data['photo']);
				}
			}

			$fabric->populate($data);
			$fabric->save();
			$this->_helper->flashMessenger->success('Fabric saved Successfully');
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
		$fabricsIds = (array)$this->_getParam('fabric', $this->_getParam('id'));
		/**
		 * @var Service_Fabric $service
		 */
		$service = $this->_helper->service('Fabric');
		$i = 0;
		foreach($fabricsIds as $fabric_id) {
			/**
			 * @var Model_Fabric $fabric
			 */
			$fabric = $service->getFabricById($fabric_id);
			if ($fabric->isEmpty()) {
				$this->_helper->flashMessenger->fail('Fabric ID (' . $fabric_id . ') NOT Found');
				continue;
			}
			$path = realpath(self::FABRICS_PATH);
			if (file_exists($path . DIRECTORY_SEPARATOR . $fabric->photo)) {
				$oldFile = Service_Photo::create(array('filename' => $fabric->photo));
				@unlink($path . DIRECTORY_SEPARATOR . $oldFile->getFilename());
				@unlink($path . DIRECTORY_SEPARATOR . $oldFile->getFilename(Model_Photo::SIZE_FABRIC));
			}
			$fabric->delete();
			$i++;
		}
		if ($i > 0) {
			$this->_helper->flashMessenger->success($i . ($i > 1?' fabrics were':' fabric was') . ' deleted');
		}
		$this->_redirect($this->_helper->url(''));
	}

}
