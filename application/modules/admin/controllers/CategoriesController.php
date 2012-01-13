<?php

class Admin_CategoriesController extends Zend_Controller_Action
{

    const ITEMS_PER_PAGE = 20;

    /**
     * @var \Entities\User
     */
    protected $_user;

    /**
     * @var Service_Category
     */
    protected $_categoriesService;

    public function init()
    {
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('admin');
        $this->_helper->getHelper('AjaxContext')->initContext('json');
        $this->_user = $this->_helper->currentUser();
        $this->_categoriesService = $this->_helper->service('Category');
    }

    public function indexAction()
    {
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
        $categoriesPaginator = $this->_categoriesService->getCategoriesPaginator($orderString);

        $this->view->paginator = $categoriesPaginator;
        $categoriesPaginator->setCurrentPageNumber($page)->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $this->view->categories = $categoriesPaginator->getCurrentItems();
        $this->view->page = $page;
    }

    public function addAction()
    {
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

    public function editAction()
    {
        $category_id = $this->_getParam('id');
        /**
         * @var Model_Category $category
         */
        $category = $this->_categoriesService->getById($category_id);
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

    public function saveAction()
    {
        $request = $this->getRequest();
        $category_id = $request->getParam('id');
        if (!empty($category_id)) {
            /**
             * @var Model_Category $category
             */
            $category = $this->_categoriesService->getById($category_id);
            if ($category->isEmpty()) {
                throw new Zend_Controller_Action_Exception('Category not found', 404);
            }
        }
        else {
            $category = $this->_categoriesService->create();
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

    public function deleteAction()
    {
        $categoryIds = (array)$this->_getParam('category', $this->_getParam('id'));
        $i = 0;
        foreach ($categoryIds as $category_id) {
            /**
             * @var Model_Category $category
             */
            $category = $this->_categoriesService->getById($category_id);
            if ($category->isEmpty()) {
                $this->_helper->flashMessenger->fail('Category ID NOT Found');
                continue;
            }
            $category->delete();
            $i++;
        }
        if ($i > 0) {
            $this->_helper->flashMessenger->success($i . ($i > 1 ? ' categories were' : ' category was') . ' deleted');
        }
        $this->_redirect($this->_helper->url(''));
    }

}
