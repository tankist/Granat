<?php

class Admin_CategoriesController extends Zend_Controller_Action {

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
		$orderString = null;
		if ($order) {
			$orderString = $order . ' ' . $orderType;
		}

		/**
		 * @var Skaya_Paginator $categoriesPaginator
		 */
		$categoriesPaginator = $this->_helper->service('Category')->getCategoriesPaginator($orderString);

		$this->view->paginator = $categoriesPaginator;
		$categoriesPaginator->setCurrentPageNumber($page)->setItemCountPerPage(self::ITEMS_PER_PAGE);
		$this->view->categories = $categoriesPaginator->getCurrentItems();
		$this->view->page = $page;
	}

	public function addAction() {
		$form = new Admin_Form_Category(array(
			'name' => 'user',
			'action' => $this->_helper->url('save'),
			'method' => Zend_Form::METHOD_POST
		));

		$sessionData = $this->_helper->sessionSaver('categoryData');
		if ($sessionData) {
			$form->populate($sessionData);
			$this->_helper->sessionSaver->delete('categoryData');
		}

		$form->removeElement('id');
		$form->prepareDecorators();
		$this->view->form = $form;
	}

	public function editAction() {
		$category_id = $this->_getParam('id');
		/**
		 * @var Model_Category $category
		 */
		$category = $this->_helper->service('Category')->getCategoryById($category_id);
		if ($category->isEmpty()) {
			throw new Zend_Controller_Action_Exception('Category not found', 404);
		}

		$form = new Admin_Form_Category(array(
			'name' => 'category',
			'action' => $this->_helper->url('save'),
			'method' => Zend_Form::METHOD_POST
		));
		$data = $category->toArray();

		$sessionData = $this->_helper->sessionSaver('categoryData');
		if ($sessionData) {
			$data = $sessionData;
			$this->_helper->sessionSaver->delete('categoryData');
		}

		$form->populate($data);
		$form->prepareDecorators();
		$this->view->form = $form;
	}

	public function saveAction() {
		$request = $this->getRequest();
		$category_id = $request->getParam('id');
		if (!empty($category_id)) {
			/**
			 * @var Model_Category $category
			 */
			$category = $this->_helper->service('Category')->getCategoryById($category_id);
			if ($category->isEmpty()) {
				throw new Zend_Controller_Action_Exception('Category not found', 404);
			}
		}
		else {
			$category = $this->_helper->service('Category')->create();
		}

		$form = new Admin_Form_Category(array(
			'name' => 'category'
		));

		if ($request->isPost() && $form->isValid($request->getPost())) {
			$data = $form->getValues();
			$category->populate($data);
			$category->save();
			$this->_helper->flashMessenger->success('Category saved Successfully');
			$this->_redirect($this->_helper->url(''));
		}
		else {
			$this->_helper->flashMessenger->addErrorsFromForm($form);
			$data = $form->getValues();
			$this->_helper->sessionSaver('categoryData', $data);
			if (!empty($category_id)) {
				$this->_redirect($this->_helper->url('edit', null, null, array('id' => $category_id)));
			}
			else {
				$this->_redirect($this->_helper->url('add'));
			}
		}
	}

	public function deleteAction() {
		$category_id = $this->_getParam('id');
		/**
		 * @var Model_Category $category
		 */
		$category = $this->_helper->service('Category')->getCategoryById($category_id);
		if ($category->isEmpty()) {
			throw new Zend_Controller_Action_Exception('Category ID NOT Found', 404);
		}
		$category->delete();
		$this->_redirect($this->_helper->url(''));
	}
	
	public function deleteCategoriesAction() {
		$request = $this->getRequest();
		$categoryIds = $request->getParam('category',array());
		
		if ( $categoryIds && is_array($categoryIds) ) {
			$i = 0;
			foreach ( $categoryIds as $id ) {
				/**
				 * @var Model_Category $category
				 */
				$category = $this->_helper->service('Category')->getCategoryById($id);
				if ( !$category->isEmpty() ) {
					$category->delete();
					$i++;
				}
			}
		}
		if ( $i ) {
			$this->_helper->flashMessenger->success($i.' category'.(($i == 1)?'':'s').' have been deleted.');
		} else {
			$this->_helper->flashMessenger->fail('No category has been deleted');
		}
		$this->_redirect($this->_helper->url(''));
	}

}