<?php

class Application_Model_Mapper_Db_Photo extends Skaya_Model_Mapper_Db_Abstract
{

    const TABLE_NAME = 'Photos';

    protected $_mapperTableName = self::TABLE_NAME;

    public function getPhotoById($id)
    {
        $photoTable = self::_getTableByName(self::TABLE_NAME);
        $photoBlob = $photoTable->fetchRowById($photo_id);
        return $this->getMappedArrayFromData($photoBlob);
    }

    public function getPhotos($order = null, $count = null, $offset = null)
    {
        $photoTable = self::_getTableByName(self::TABLE_NAME);
        $photoBlob = $photoTable->fetchAll(null, $order, $count, $offset);
        return $this->getMappedArrayFromData($photoBlob);
    }

    public function getPhotosPaginator($order = null)
    {
        $photoTable = self::_getTableByName(self::TABLE_NAME);
        $select = $photoTable->select();
        if ($order) {
        	$select->order($this->_mapOrderStatement($order));
        }
        $paginator = Skaya_Paginator::factory($select, 'DbSelect');
        $paginator->addFilter(new Zend_Filter_Callback(array(
        	'callback' => array($this, 'getMappedArrayFromData')
        )));
        return $paginator;
    }


}

