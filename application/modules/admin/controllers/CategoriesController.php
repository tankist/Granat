<?php

/**
 * @class Admin_CategoriesController
 */
class Admin_CategoriesController extends Zend_Controller_Action
{

    const ITEMS_PER_PAGE = 20;

    /**
     * @var Service_Category
     */
    protected $_service;

    public function init()
    {
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('admin');
        $this->_helper->getHelper('AjaxContext')->initContext();
        $this->_service = new Service_Category($this->_helper->Em());
    }

    public function preDispatch()
    {
        $this->_helper->navigator();
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $page = $request->getParam('page', 1);
        $order = $request->getParam('order');
        $orderType = $request->getParam('orderType', 'ASC');

        $categoriesPaginator = $this->_service->getPaginator(array(
            'order' => $order,
            'orderType' => $orderType
        ));
        $categoriesPaginator
            ->setCurrentPageNumber($page)
            ->setItemCountPerPage(self::ITEMS_PER_PAGE);

        $this->view->assign(array(
            'categories' => $categoriesPaginator,
            'page' => $page,
            'order' => $order,
            'orderType' => $orderType
        ));
    }

    public function addAction()
    {
        $form = new Admin_Form_Category(array(
            'name' => 'user',
            'action' => $this->_helper->url('save')
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
        /** @var \Entities\Category $category */
        $category = $this->_service->getById($category_id);
        if (!$category) {
            throw new Zend_Controller_Action_Exception('Category not found', 404);
        }

        $form = new Admin_Form_Category(array(
            'name' => 'category',
            'action' => $this->_helper->url('save')
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
        $formParams = array();
        if (($category_id = $request->getPost('id'))) {
            /** @var $category \Entities\Category */
            if (!($category = $this->_service->getById($category_id))) {
                throw new Zend_Controller_Action_Exception('Category not found', 404);
            }
        }
        $form = new Admin_Form_category($formParams);

        if ($request->isPost() && $form->isValid($request->getPost())) {
            $data = $form->getValues();
            if (!isset($category)) {
                $category = $this->_service->create($data['title']);
            }
            $category->populate($data);
            $this->_service->save($category);
            $this->_helper->flashMessenger->success('Category "' . $category->getTitle() . '" saved Successfully');
            $this->_service->getPaginator()->setItemCountPerPage(self::ITEMS_PER_PAGE)->clearPageItemCache();
            $this->_redirect($this->_helper->url(''));
        }
        else {
            $this->_helper->flashMessenger->addErrorsFromForm($form);
            $this->_helper->sessionSaver('categoryData', $form->getValues());
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
            $category = $this->_service->getById($category_id);
            if (!$category) {
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
