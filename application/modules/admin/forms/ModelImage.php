<?php
/**
 * @property Zend_Form_Element_File $name
 */
class Admin_Form_ModelImage extends Admin_Form_Abstract {

	protected $_imagesPath;

	public function init() {
		$this->setEnctype(self::ENCTYPE_MULTIPART);

		$image = new Zend_Form_Element_File('name', array('size' => 15, 'label' => 'Model photo: ', 'multiple' => ''));
		$image
			->setTransferAdapter(new Zend_File_Transfer_Adapter_Http(array('magicFile' => '/usr/local/apache/conf/magic')))
			->addValidator('Size', false, 8*1024000)
			->addValidator('IsImage', false, array('image/gif', 'image/jpeg', 'image/png', 'image/pjpeg'));
		$image->addFilter(new Skaya_Filter_File_Rename($this->getImagesPath()));
		$image->addFilter(new Skaya_Filter_File_Thumbnail(
			Model_Model::getThumbnailPack()
		));

		$image
			->setDecorators(array('File'))
			->addDecorator('HtmlTag', array('tag' => 'dd'))
			->addDecorator('Label', array('tag' => 'dt'));
		$this->addElement($image);
		$this->setDecorators(array('FormElements', array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')), 'Form'));
	}

	public function getImagesPath() {
		return $this->_imagesPath;
	}

	public function setImagesPath($imagesPath) {
		$this->_imagesPath = $imagesPath;
		return $this;
	}
}