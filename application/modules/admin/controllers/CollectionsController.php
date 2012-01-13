<?php

class Admin_CollectionsController extends Zend_Controller_Action
{

    const ITEMS_PER_PAGE = 20;

    /**
     * @var \Entities\User
     */
    protected $_user;

    public function init()
    {
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('admin');
        $this->_helper->getHelper('AjaxContext')->initContext('json');
        $this->_user = $this->_helper->currentUser();
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $page = $request->getParam('page', 1);
        $this->view->order = $order = $request->getParam('order');
        $this->view->orderType = $orderType = $request->getParam('orderType', 'ASC');
        /**
         * @var Skaya_Paginator $collectionsPaginator
         */
        $orderString = null;
        if ($order) {
            $orderString = $order . ' ' . $orderType;
        }

        $collectionsPaginator = $this->_helper->service('Collection')->getCollectionsPaginator($orderString);

        $this->view->paginator = $collectionsPaginator;
        $collectionsPaginator->setCurrentPageNumber($page)->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $this->view->collections = $collectionsPaginator->getCurrentItems();
        $this->view->page = $page;
    }

    public function addAction()
    {
        $form = new Admin_Form_Collection(array(
            'name' => 'user',
            'action' => $this->_helper->url('save'),
            'method' => Zend_Form::METHOD_POST
        ));

        $sessionData = $this->_helper->sessionSaver('collectionData');
        if ($sessionData) {
            $form->populate($sessionData);
            $this->_helper->sessionSaver->delete('collectionData');
        }

        $form->removeElement('id');
        $form->prepareDecorators();
        $this->view->form = $form;
    }

    public function editAction()
    {
        $collection_id = $this->_getParam('id');
        /**
         * @var Model_Collection $collection
         */
        $collection = $this->_helper->service('Collection')->getCollectionById($collection_id);
        if ($collection->isEmpty()) {
            throw new Zend_Controller_Action_Exception('Collection not found', 404);
        }

        $models = $collection->getModels();
        $imagesData = array();
        foreach ($models as /** @var Model_Model $model */
                 $model) {
            $imagesPath = $this->_helper->imagePath($model);
            $image = $model->getMainPhoto();
            $imagesData[$model->id] = array(
                'id' => $model->id,
                'name' => $model->name,
                'thumb' => $image->getFilename(\Entities\Model\Photo::SIZE_SMALL),
                'path' => $imagesPath
            );
        }

        $form = new Admin_Form_Collection(array(
            'name' => 'collection',
            'action' => $this->_helper->url('save'),
            'method' => Zend_Form::METHOD_POST,
            'models' => $imagesData
        ));
        $data = $collection->toArray();

        $sessionData = $this->_helper->sessionSaver('collectionData');
        if ($sessionData) {
            $data = $sessionData;
            $this->_helper->sessionSaver->delete('collectionData');
        }

        $form->populate($data);
        $form->prepareDecorators();
        $this->view->form = $form;
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $collection_id = $request->getParam('id');
        if (!empty($collection_id)) {
            $collection = $this->_helper->service('Collection')->getCollectionById($collection_id);
            if ($collection->isEmpty()) {
                throw new Zend_Controller_Action_Exception('Collection not found', 404);
            }
        }
        else {
            $collection = $this->_helper->service('Collection')->create();
        }

        $models = $collection->getModels();
        $filter = new Skaya_Filter_Array_Map('name', 'id');

        $form = new Admin_Form_Collection(array(
            'name' => 'collection',
            'models' => $filter->filter($models->toArray())
        ));

        if ($request->isPost() && $form->isValid($request->getPost())) {
            $data = $form->getValues();
            $collection->populate($data);
            $collection->save();
            $this->_helper->flashMessenger->addMessage(array('message' => 'Collection saved Successfully', 'status' => 'success'));
            $this->_redirect($this->_helper->url(''));
        }
        else {
            $this->_helper->flashMessenger->addErrorsFromForm($form);
            $data = $form->getValues();
            $this->_helper->sessionSaver('collectionData', $data);
            if (!empty($collection_id)) {
                $this->_redirect($this->_helper->url('edit', null, null, array('id' => $collection_id)));
            }
            else {
                $this->_redirect($this->_helper->url('add'));
            }
        }
    }

    public function deleteAction()
    {
        $collection_id = $this->_getParam('id');
        $collection = $this->_helper->service('Collection')->getCollectionById($collection_id);
        if ($collection->isEmpty()) {
            throw new Zend_Controller_Action_Exception('Collection ID NOT Found', 404);
        }
        $collection->delete();
        $this->_redirect($this->_helper->url(''));
    }

    protected function _mapModelsForm($model, $index)
    {
        return array($model['id'], $model);
    }

}
