<?php

class Sch_Form_Element_PictureFile extends Zend_Form_Element_File
{
    /**
     * Директория с файлом
     *
     * @var string
     */
    protected $rootDocumentPath = '/..';
    /**
     * Путь к файлу
     *
     * @var string
     */
    protected $filePath = '';

    /**
     * Устанавливаем значение - имя картинки
     *
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }
}
