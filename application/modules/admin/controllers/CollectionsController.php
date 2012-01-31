<?php

/**
 * @class Admin_CollectionsController
 */
class Admin_CollectionsController extends Zend_Controller_Action
{

    const ITEMS_PER_PAGE = 20;

    /**
     * @var Service_Collection
     */
    protected $_service;

    public function init()
    {
        $this->_service = new Service_Collection($this->_helper->Em());
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('admin');
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

        $collectionsPaginator = $this->_service->getPaginator(array(
            'order' => $order,
            'orderType' => $orderType
        ));
        $collectionsPaginator
            ->setCurrentPageNumber($page)
            ->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $this->view->assign(array(
            'order' => $order,
            'orderType' => $orderType,
            'collections' => $collectionsPaginator,
            'page' => $page
        ));
    }

    public function addAction()
    {
        $form = new Admin_Form_Collection(array(
            'name' => 'collection',
            'action' => $this->_helper->url->url(array('action' => 'save'), 'admin-default')
        ));

        if (($data = $this->_helper->sessionSaver('collectionData'))) {
            $form->isValid($data);
            $this->_helper->sessionSaver->delete('collectionData');
        }

        $form->removeElement('id');
        $form->prepareDecorators();
        $this->view->form = $form;
    }

    public function editAction()
    {
        $collection_id = $this->_getParam('id');
        /** @var Entities\Collection $collection */
        $collection = $this->_service->getById($collection_id);
        if (!$collection) {
            throw new Zend_Controller_Action_Exception('Collection not found', 404);
        }

        $models = $collection->getModels();
        $imagesData = array();
        foreach ($models as $model) {
            $imagesPath = $this->_helper->attachmentPath($model);
            $image = $model->getMainPhoto();
            $imagesData[$model->getId()] = array(
                'id' => $model->getId(),
                'name' => $model->getTitle(),
                'thumb' => $image->getFilename(\Entities\Model\Photo::THUMBNAIL_SMALL),
                'path' => $imagesPath
            );
        }

        $form = new Admin_Form_Collection(array(
            'name' => 'collection',
            'action' => $this->_helper->url->url(array('action' => 'save'), 'admin-default'),
            'models' => $imagesData
        ));
        $form->populateEntity($collection);

        $data = $this->_helper->sessionSaver('collectionData');
        if ($data) {
            $form->isValid($data);
            $this->_helper->sessionSaver->delete('collectionData');
        }

        $form->prepareDecorators();
        $this->view->form = $form;
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $formParams = array();
        if (($collection_id = $request->getPost('id'))) {
            /** @var $collection \Entities\Collection */
            if (!($collection = $this->_service->getById($collection_id))) {
                throw new Zend_Controller_Action_Exception('Collection not found', 404);
            }
            $filter = new Sch_Filter_Array_EntitiesCollection('title', 'id');
            $formParams['models'] = $filter->filter($collection->getModels());
        }
        $form = new Admin_Form_Collection($formParams);

        if ($request->isPost() && $form->isValid($request->getPost())) {
            $data = $form->getValues();
            if (!isset($collection)) {
                $collection = $this->_service->create($data['title']);
            }
            $collection->populate($data);
            if (!empty($data['mainModelId'])) {
                if (!($mainModel = $collection->getMainModel()) || ($mainModel->getId() != $data['mainModelId'])) {
                    $modelService = new Service_Model($this->_helper->Em());
                    if (
                        ($mainModel = $modelService->getById($data['mainModelId'])) &&
                        $mainModel->getCollection()->getId() == $collection->getId()) {
                            $collection->setMainModel($mainModel);
                    }
                }
            }
            $this->_service->save($collection);
            $this->_helper->flashMessenger->success('Collection "' . $collection->getTitle() . '" saved Successfully');
            $this->_service->getPaginator()->setItemCountPerPage(self::ITEMS_PER_PAGE)->clearPageItemCache();
            $this->_redirect($this->_helper->url(''));
        }
        else {
            $this->_helper->flashMessenger->addErrorsFromForm($form);
            $this->_helper->sessionSaver('collectionData', $form->getValues());
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
        $collection = $this->_service->getById($this->_getParam('id'));
        if (!$collection) {
            throw new Zend_Controller_Action_Exception('Collection ID NOT Found', 404);
        }
        $this->_service->delete($collection);
        $this->_service->getPaginator()->setItemCountPerPage(self::ITEMS_PER_PAGE)->clearPageItemCache();
        $this->_redirect($this->_helper->url(''));
    }

}
