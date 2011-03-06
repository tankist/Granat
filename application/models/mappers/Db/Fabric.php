<?php

class Application_Model_Mapper_Db_Fabric extends Skaya_Model_Mapper_Db_Abstract
{

    const TABLE_NAME = 'Fabrics';

    protected $_mapperTableName = self::TABLE_NAME;

    public function getFabricById($id)
    {
        $fabricTable = self::_getTableByName(self::TABLE_NAME);
        $fabricBlob = $fabricTable->fetchRowById($fabric_id);
        return $this->getMappedArrayFromData($fabricBlob);
    }

    public function getFabrics($order = null, $count = null, $offset = null)
    {
        $fabricTable = self::_getTableByName(self::TABLE_NAME);
        $fabricBlob = $fabricTable->fetchAll(null, $order, $count, $offset);
        return $this->getMappedArrayFromData($fabricBlob);
    }

    public function getFabricsPaginator($order = null)
    {
        $fabricTable = self::_getTableByName(self::TABLE_NAME);
        $select = $fabricTable->select();
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

