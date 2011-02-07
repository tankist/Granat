<?php
class Model_Mapper_Db_StoreDesign extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'storeDesign';
	const STRUCTURE_TABLE_NAME = 'storeStructure';
	
	protected $_fieldMapping = array(
		'productsPerPage' => 'products_per_page',
		'productsOrder' => 'products_order',
		'embedWidth' => 'embed_width',
		'embedHeight' => 'embed_height',
		'layoutType' => 'layout_type',
	);
	
	public function getStoreElementsVisibility($store_id) {
		$elements = self::_getStoreStructureTable()->fetchAllByStoreId((int)$store_id);
		return $this->getMappedArrayFromData($elements);
	}
	
	public function getStoreDesign($store_id) {
		$elements = self::_getTableByName(self::TABLE_NAME)->fetchRowByStoreId((int)$store_id);
		return $this->getMappedArrayFromData($elements);
	}
	
	/**
	* @return Model_DbTable_Products
	*/
	protected static function _getStoreStructureTable() {
		return self::_getTableByName(self::STRUCTURE_TABLE_NAME);
	}
	
	public function save($data) {
		$elements = $data['design']['elements'];
		$layout = $data['design']['layout'];
		$layout['store_id'] = $data['id'];
		
		$unmappedData = $this->unmap($layout);
		$this->_mapperTableName = self::TABLE_NAME;
		$row = $this->_findOrCreateRowByData($unmappedData);
		$row->save();
		
		$this->_mapperTableName = self::STRUCTURE_TABLE_NAME;
		foreach ( $elements as $key=>$value ) {
			$element = array(
							'store_id'=>$data['id'],
							'section_id'=>$key,
							'is_visible'=>$value
						);
			$unmappedData = $this->unmap($element);
			$row = $this->_findOrCreateRowByData($unmappedData);
			$row->save();
		}
	}
}
?>
