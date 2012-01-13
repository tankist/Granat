<?php
class Sch_Form_Decorator_File extends Zend_Form_Decorator_File
{
    protected $_attribBlacklist = array('helper', 'placement', 'separator', 'value', 'rootDocumentPath', 'filePath');
}