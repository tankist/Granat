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
			$filename = $data['name'];
			$tokens = explode('.', $filename);
			$extension = array_pop($tokens);
			/**
			 * @var Model_Photo $image
			 */
			$image = $this->_helper->service('Photo')->create(array(
				'hash' => join('.', $tokens),
				'extension' => $extension
			));
			if ($model && !$model->isEmpty()) {
				$model->addImage($image);
				$id = $image->id;
			} else {
				$path = $this->_helper->sessionSaver('modelImagesPath');
				if (!is_array($path)) {
					$path = array();
				}
				$id = md5(time());
				$path[$id] = array('path' => $imagesPath, 'name' => $image->getFilename());
				$this->_helper->sessionSaver('modelImagesPath', $path);
			}

			$this->view->assign(array(
				'path' => $imagesPath,
				'name' => $image->getFilename(),
				'thumb' => $image->getFilename(Model_Photo::SIZE_SMALL),
				'id' => $id
			));

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
		// action body
	}

}