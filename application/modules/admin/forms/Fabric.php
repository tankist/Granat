<?php
/**
 * @property Zend_Form_Element_Hidden $id
 * @property Zend_Form_Element_Text $name
 * @property Zend_Form_Element_File $photo
 * @property Zend_Form_Element_Button $submit
 */
class Admin_Form_Fabric extends Admin_Form_Abstract
{

    protected $_imagePath = '';

    public function init()
    {
        $this->setEnctype(self::ENCTYPE_MULTIPART);

        $image = new Zend_Form_Element_File('photo', array('size' => 15, 'label' => 'Image'));
        $image
            ->setTransferAdapter(new Zend_File_Transfer_Adapter_Http(array('magicFile' => '/usr/local/apache/conf/magic')))
            ->addValidator('Size', false, 8 * 1024000)
            ->addValidator('IsImage', false, array('image/gif', 'image/jpeg', 'image/png', 'image/pjpeg'));

        $this
            ->addElement('hidden', 'id', array('label' => 'id'))
            ->addElement('text', 'name', array('label' => 'Name', 'required' => true))
            ->addElement('textarea', 'description', array('label' => 'Description'))
            ->addElement($image)
            ->addElement('button', 'submit', array('label' => 'Save', 'type' => 'submit'));

    }

    public function prepareDecorators()
    {
        parent::prepareDecorators();
        if ($this->id instanceof Zend_Form_Element) {
            $this->id->setDecorators(array('ViewHelper'));
        }
    }

    public function setImagePath($imagePath)
    {
        $this->_imagePath = $imagePath;
        return $this;
    }

    public function getImagePath()
    {
        return $this->_imagePath;
    }

}
