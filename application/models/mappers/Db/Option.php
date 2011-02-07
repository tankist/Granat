<?php
class Model_Mapper_Db_Option extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'options';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	/**
	* Return all options for the given product
	* 
	* @param int $product_id
	* @return array
	*/
	public function getProductOptions($product_id) {
		$optionsTable = self::_getTableByName(self::TABLE_NAME);
		$optionsDataset = $optionsTable->fetchAllByProductId((int)$product_id,array('display_order'));
		return $this->getMappedArrayFromData($optionsDataset);
	}
	
	/**
	* Return one option for the given product
	* 
	* @param int $product_id
	* @param int $option_id
	* @return array
	*/
	public function getProductOptionById($product_id, $option_id) {
		$optionsTable = self::_getTableByName(self::TABLE_NAME);
		$option = $optionsTable->fetchRowByProductIdAndId((int)$product_id, (int)$option_id);
		return $this->getMappedArrayFromData($option);
	}
	
	/**
	* Return one option for the given product (identify by option name)
	* 
	* @param int $product_id
	* @param string $option_name
	* @return array
	*/
	public function getProductOptionByName($product_id, $option_name) {
		$optionsTable = self::_getTableByName(self::TABLE_NAME);
		$option = $optionsTable->fetchRowByProductIdAndName((int)$product_id, $option_name);
		return $this->getMappedArrayFromData($option);
	}
	
	/**
	* Deletes all options for the given product
	* 
	* @param int $product_id
	* @return Model_Mapper_Db_Product
	*/
	public function deleteProductOptions($product_id) {
		$optionsTable = self::_getTableByName(self::TABLE_NAME);
		$optionsTable->deleteByProductId((int)$product_id);
		return $this;
	}
	
}
?>
