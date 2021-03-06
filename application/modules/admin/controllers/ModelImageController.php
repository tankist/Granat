<?php

class Admin_ModelImageController extends Zend_Controller_Action {

	/**
	 * @var Model_User
	 */
	protected $_user = null;

	public $contexts = array(
		'upload' => true,
		'delete' => true
	);

	public function init() {
		$this->_helper->getHelper('AjaxContext')->initContext('json');
		$this->_helper->getHelper('ContextSwitch')->clearHeaders('json')->initContext('json');
		$this->_user = $this->_helper->user();
	}

	public function indexAction() {
		// action body
	}

	public function uploadAction() {
        /** @var $log Zend_Log */
        $log = $this->getInvokeArg('bootstrap')->log;
		$request = $this->getRequest();
		$model_id = $request->getParam('model_id');
		/**
		 * @var Model_Model $model
		 */
		$model = $this->_helper->service('Model')->getModelById($model_id);

		$imagesPath = $this->_helper->imagePath($model);
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
                if (!empty($files) && $big = $files[Model_ModelPhoto::SIZE_BIG]) {
                    /**
                     * @var Model_ModelPhoto $image
                     */
                    $image = $this->_helper->service('ModelPhoto')->create();
                    $image->setFilename($big, Model_ModelPhoto::SIZE_BIG);

                    if (!$model->isEmpty()) {
                        $model->addPhoto($image);
                        $id = $image->id;
                    }
                    else {
                        $id = md5(time());
                    }
                    $imageData = array(
                        'path' => $imagesPath,
                        'name' => $image->getFilename(),
                        'thumb' => $image->getFilename(Model_ModelPhoto::SIZE_SMALL),
                        'id' => $id
                    );
                    if ($model->isEmpty()) {
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

	public function deleteAction() {
		$request = $this->getRequest();
		$model_id = $request->getParam('model_id');
		$image_id = $request->getParam('id');

		/**
		 * @var Model_Model $model
		 */
		$model = $this->_helper->service('Model')->getModelById($model_id);
		if (!$model->isEmpty()) {
			/**
			 * @var Model_ModelPhoto $image
			 */
			$image = $model->getPhotoById($image_id);
			if (!$image->isEmpty()) {
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
			 * @var Model_ModelPhoto $image
			 */
			$image = $this->_helper->service('ModelPhoto')->create(array(
				'filename' => $imageData['name']
			));
			$this->_delete($image, $imageData['path']);
			unset($images[$image_id]);
			$this->_helper->sessionSaver('modelImagesPath', $images);
		}
	}

	protected function _delete(Model_ModelPhoto $image, $path = null) {
		if (!$path || !file_exists($path)) {
			$path = $this->_helper->imagePath($image->getModel());
		}
		$sizes = array('');
		foreach (array_merge($sizes, array_keys(Model_ModelPhoto::getThumbnailPack())) as $size) {
			$filename = $image->getFilename($size);
			$filePath = realpath($path . DIRECTORY_SEPARATOR . $filename);
			if ($filePath && file_exists($filePath) && is_writable($filePath)) {
				unlink($filePath);
			}
		}
		return $this;
	}

}
