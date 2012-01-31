<?php

/**
 * @class Admin_ModelImageController
 */
class Admin_ModelImageController extends Zend_Controller_Action
{

    public $contexts = array(
        'upload' => true,
        'delete' => true
    );

    /**
     * @var \Entities\User
     */
    protected $_user = null;

    /**
     * @var Service_ModelPhoto
     */
    protected $_service;

    /**
     * @var Service_Model
     */
    protected $_modelService;

    public function init()
    {
        $em = $this->_helper->Em();
        $this->_service = new Service_ModelPhoto($em);
        $this->_modelService = new Service_Model($em);
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('admin');
        $this->_helper->getHelper('AjaxContext')->initContext('json');
        $this->_helper->getHelper('ContextSwitch')->clearHeaders('json')->initContext('json');
        $this->_user = $this->_helper->currentUser();
    }

    public function indexAction()
    {
        // action body
    }

    public function uploadAction()
    {
        /** @var $log Zend_Log */
        $log = $this->getInvokeArg('bootstrap')->log;
        $request = $this->getRequest();
        $model_id = $request->getParam('model_id');
        /**
         * @var Model_Model $model
         */
        $model = $this->_modelService->getById($model_id);

        $imagesPath = $this->_helper->attachmentPath($model);
        $form = new Admin_Form_ModelImage(array(
            'name' => 'modelImage',
            'imagesPath' => $imagesPath
        ));

        if ($request->isPost() && $form->isValid($request->getPost())) {
            $data = $form->getValues();
            if ($file = $data['name']) {
                $log->info($_FILES);
                $log->info($imagesPath);
                $files = $this->_helper->avatar->upload($file, $imagesPath);
                if (!empty($files) && $big = $files[\Entities\Model\Photo::THUMBNAIL_BIG]) {
                    /**
                     * @var \Entities\Model\Photo $image
                     */
                    $image = $this->_service->create();
                    $image->setFilename($big, \Entities\Model\Photo::THUMBNAIL_BIG);

                    if ($model) {
                        $model->addPhoto($image);
                        $id = $image->getId();
                    }
                    else {
                        $id = md5(time());
                    }
                    $imageData = array(
                        'path' => $imagesPath,
                        'name' => $image->getFilename(),
                        'thumb' => $image->getFilename(\Entities\Model\Photo::THUMBNAIL_SMALL),
                        'id' => $id
                    );
                    if (!$model) {
                        $path = $this->_helper->sessionSaver('modelImagesPath');
                        if (!is_array($path)) {
                            $path = array();
                        }
                        $path[$id] = $imageData;
                        $this->_helper->sessionSaver('modelImagesPath', $path);
                    }

                    $this->view->assign($imageData);
                }
            }

        } else {
            $errorString = '';

            foreach ($form->getMessages() as $element => $errors) {
                $errorString .= $form->getElement($element)->getLabel();
                if (count($errors) > 1) {
                    $errorString . '<br /><br />';
                }
                $errorString .= join('<br />', $errors);
            }

            $this->view->error = $errorString;
        }
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $model_id = $request->getParam('model_id');
        $image_id = $request->getParam('id');

        /**
         * @var Model_Model $model
         */
        $model = $this->_modelService->getById($model_id);
        if ($model) {
            /**
             * @var \Entities\Model\Photo $image
             */
            $image = $model->getPhotoById($image_id);
            if ($image) {
                $this->_delete($image);
                $image->delete();
            }
        }
        else {
            $images = $this->_helper->sessionSaver('modelImagesPath');
            $imageData = $images[$image_id];
            if (!$imageData) {
                $this->view->error = 'Image was not found';
                return;
            }
            /**
             * @var \Entities\Model\Photo $image
             */
            $image = $this->_service->create(array(
                'filename' => $imageData['name']
            ));
            $this->_delete($image, $imageData['path']);
            unset($images[$image_id]);
            $this->_helper->sessionSaver('modelImagesPath', $images);
        }
    }

    protected function _delete(\Entities\Model\Photo $image, $path = null)
    {
        if (!$path || !file_exists($path)) {
            $path = $this->_helper->attachmentPath($image->getModel());
        }
        $sizes = array('');
        foreach (array_merge($sizes, array_keys(\Entities\Model\Photo::getThumbnailPack())) as $size) {
            $filename = $image->getFilename($size);
            $filePath = realpath($path . DIRECTORY_SEPARATOR . $filename);
            if ($filePath && file_exists($filePath) && is_writable($filePath)) {
                unlink($filePath);
            }
        }
        return $this;
    }

}
