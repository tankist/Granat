<?php
/**
 * @property Zend_Form_Element_File $name
 */
class Admin_Form_ModelImage extends Admin_Form_Abstract {

	protected $_imagesPath;

	public function init() {
		$this->setEnctype(self::ENCTYPE_MULTIPART);

		$image = new Zend_Form_Element_File('name', array('size' => 15, 'label' => 'Model photo: ', 'multiple' => 'multiple'));
		$image
			->setTransferAdapter(new Zend_File_Transfer_Adapter_Http(array('magicFile' => '/usr/local/apache/conf/magic')))
			->addValidator('Size', false, 8*1024000)
			->addValidator('IsImage', false, array('image/gif', 'image/jpeg', 'image/png', 'image/pjpeg'))
            ->addFilter(new Skaya_Filter_File_Rename($this->getImagesPath()))
			->setIsArray(true);

        if ($imagesPath = $this->getImagesPath()) {
            $image->setDestination($imagesPath);
        }

		$this
			->addElement($image)
			->addElement('button', 'upload', array('label' => 'Upload'));
	}

	public function getImagesPath() {
		return $this->_imagesPath;
	}

	public function setImagesPath($imagesPath) {
		$this->_imagesPath = $imagesPath;
		return $this;
	}

	public function prepareDecorators() {
		$this->setDecorators(array(
			new Zend_Form_Decorator_ViewScript(array('viewScript' => 'forms/upload.phtml')),
			'Form'
		));

		$this->name->setDecorators(array('File'));
		$this->upload->setDecorators(array('ViewHelper'));
	}
}
