<?php

/**
 * @class Admin_ModelsController
 */
class Admin_ModelsController extends Zend_Controller_Action
{

    const ITEMS_PER_PAGE = 20;

    /**
     * @var Service_Model
     */
    protected $_service;

    /**
     * @var Service_Collection
     */
    protected $_collectionService;

    /**
     * @var Service_Category
     */
    protected $_categoryService;

    public function init()
    {
        $em = $this->_helper->Em();
        $this->_service = new Service_Model($em);
        $this->_collectionService = new Service_Collection($em);
        $this->_categoryService = new Service_Category($em);
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('admin');
        $this->_helper->getHelper('AjaxContext')->initContext();
    }

    public function preDispatch()
    {
        $this->_helper->navigator();
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $page = $request->getParam('page', 1);
        $this->view->order = $order = $request->getParam('order');
        $this->view->orderType = $orderType = $request->getParam('orderType', 'ASC');
        /** @var $modelsPaginator Zend_Paginator */
        $modelsPaginator = $this->_service->getPaginator(array('order' => $order, 'orderType' => $orderType));

        $this->view->paginator = $modelsPaginator;
        $modelsPaginator->setCurrentPageNumber($page)->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $this->view->models = $modelsPaginator->getCurrentItems();
        $this->view->page = $page;
    }

    public function addAction()
    {
        $collections = $this->_collectionService->getCollections('name');
        $categories = $this->_categoryService->getCategories('name');
        $filter = new Skaya_Filter_Array_Map('name', 'id');

        $form = new Admin_Form_Model(array(
            'name' => 'user',
            'action' => $this->_helper->url('save'),
            'method' => Zend_Form::METHOD_POST,
            'collections' => $filter->filter($collections->toArray()),
            'categories' => $filter->filter($categories->toArray()),
            'images' => $this->_helper->sessionSaver('modelImagesPath')
        ));

        $sessionData = $this->_helper->sessionSaver('modelData');
        if ($sessionData) {
            $form->populate($sessionData);
            $this->_helper->sessionSaver->delete('modelData');
        }

        $imageForm = new Admin_Form_ModelImage(array(
            'name' => 'modelImage',
            'action' => $this->_helper->url('upload', 'model-image'),
            'imagesPath' => ''
        ));
        $imageForm->prepareDecorators();

        $sessionData = $this->_helper->sessionSaver('modelData');
        if ($sessionData) {
            $form->populate($sessionData);
            $this->_helper->sessionSaver->delete('modelData');
        }

        $form->removeElement('id');
        $form->prepareDecorators();
        $this->view->form = $form;
        $this->view->formImage = $imageForm;
    }

    public function editAction()
    {
        $model_id = $this->_getParam('id');
        /**
         * @var Model_Model $model
         */
        $model = $this->_service->getById($model_id);
        if (!$model) {
            throw new Zend_Controller_Action_Exception('Model not found', 404);
        }

        $collections = $this->_collectionService->getCollections('name');
        $categories = $this->_categoryService->getCategories('name');
        $filter = new Skaya_Filter_Array_Map('name', 'id');

        $images = $model->getPhotos();
        $imagesData = $this->_helper->sessionSaver('modelImagesPath');
        $imagesPath = $this->_helper->attachmentPath($model);
        foreach ($images as /** @var \Entities\Model\Photo $image */
                 $image) {
            $imagesData[$image->getId()] = array(
                'id' => $image->getId(),
                'name' => $image->getFilename(),
                'thumb' => $image->getFilename(\Entities\Model\Photo::THUMBNAIL_SMALL),
                'path' => $imagesPath
            );
        }

        $form = new Admin_Form_Model(array(
            'name' => 'model',
            'action' => $this->_helper->url('save'),
            'method' => Zend_Form::METHOD_POST,
            'collections' => $filter->filter($collections->toArray()),
            'categories' => $filter->filter($categories->toArray()),
            'images' => $imagesData
        ));
        $data = $model->toArray();
        $data['modelTitle'] = $model->getMainPhoto()->id;

        $sessionData = $this->_helper->sessionSaver('modelData');
        if ($sessionData) {
            $data = $sessionData;
            $this->_helper->sessionSaver->delete('modelData');
        }

        $form->populate($data);
        $form->prepareDecorators();
        $this->view->form = $form;

        $imageForm = new Admin_Form_ModelImage(array(
            'name' => 'modelImage',
            'action' => $this->_helper->url('upload', 'model-image'),
            'imagesPath' => ''
        ));
        $imageForm->prepareDecorators();
        $this->view->formImage = $imageForm;
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        $model_id = $request->getParam('id');
        if (!empty($model_id)) {
            /**
             * @var Model_Model $model
             */
            $model = $this->_service->getById($model_id);
            if (!$model) {
                throw new Zend_Controller_Action_Exception('Model not found', 404);
            }
        }
        else {
            /**
             * @var Model_Model $model
             */
            $model = $this->_service->create();
        }

        $collections = $this->_collectionService->getCollections('name');
        $categories = $this->_categoryService->getCategories('name');
        $filter = new Skaya_Filter_Array_Map('name', 'id');

        $images = $model->getPhotos();
        $imagesData = $this->_helper->sessionSaver('modelImagesPath');
        $imagesPath = $this->_helper->attachmentPath($model);
        foreach ($images as /** @var \Entities\Model\Photo $image */
                 $image) {
            $imagesData[$image->getId()] = array(
                'id' => $image->getId(),
                'name' => $image->getFilename(),
                'thumb' => $image->getFilename(\Entities\Model\Photo::THUMBNAIL_SMALL),
                'path' => $imagesPath
            );
        }

        $form = new Admin_Form_Model(array(
            'name' => 'model',
            'collections' => $filter->filter($collections->toArray()),
            'categories' => $filter->filter($categories->toArray()),
            'images' => $imagesData
        ));

        if ($request->isPost() && $form->isValid($request->getPost())) {
            $data = $form->getValues();
            $model->populate($data);
            $model->save();

            $images = (array)$this->_helper->sessionSaver('modelImagesPath');
            $modelFolder = $this->_helper->attachmentPath($model);
            /** @var $photoService Service_ModelPhoto */
            $photoService = new Service_ModelPhoto($this->_helper->Em());
            $titleChanged = false;
            foreach ($images as $imageData) {
                $path = $imageData['path'];
                $image = $photoService->create(array(
                    'filename' => $imageData['name']
                ));
                foreach (array_merge(array(''), array_keys(\Entities\Model\Photo::getThumbnailPack())) as $size) {
                    $filename = $image->getFilename($size);
                    $filePath = realpath($path . DIRECTORY_SEPARATOR . $filename);
                    if (is_readable($filePath) && is_file($filePath)) {
                        rename($filePath, realpath($modelFolder) . DIRECTORY_SEPARATOR . $filename);
                    }
                }
                $model->addPhoto($image);
                if ($data['modelTitle'] == $imageData['id']) {
                    $model->setMainPhoto($image);
                    $titleChanged = true;
                }
            }
            $this->_helper->sessionSaver->delete('modelImagesPath');

            if (!$titleChanged && (int)$data['modelTitle'] > 0) {
                $mainPhoto = $photoService->getById((int)$data['modelTitle']);
                if ($mainPhoto) {
                    $model->setMainPhoto($mainPhoto);
                }
            }

            $this->_helper->flashMessenger->success('Model saved Successfully');
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

    public function deleteAction()
    {
        $modelsIds = (array)$this->_getParam('model', $this->_getParam('id'));
        /**
         * @var Service_Model $service
         */
        $service = $this->_service;
        $i = 0;
        foreach ($modelsIds as $model_id) {
            /**
             * @var Model_Model $model
             */
            $model = $service->getById($model_id);
            if (!$model) {
                $this->_helper->flashMessenger->fail('Model ID NOT Found');
                continue;
            }
            $modelPhotosPath = realpath($this->_helper->attachmentPath($model));
            if ($modelPhotosPath) {
                $iterator = new RecursiveDirectoryIterator($modelPhotosPath);
                foreach ($iterator as /** @SplFileInfo */
                         $file) {
                    if ($file->isFile()) {
                        $path = $file->getPathname();
                        unlink($path);
                    }
                }
                unlink($modelPhotosPath);
            }
            $model->delete();
            $i++;
        }
        if ($i > 0) {
            $this->_helper->flashMessenger->success($i . ($i > 1 ? ' models were' : ' model was') . ' deleted');
        }
        $this->_redirect($this->_helper->url(''));
    }

}
