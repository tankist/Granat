<?php
class Model_Mapper_Db_ProductImage extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'productImages';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	public function map($data = array()) {
		if (!empty($data['image'])) {
			$data['ext'] = substr($data['image'],strrpos($data['image'], '.'));
			$data['name'] = basename($data['image'], $data['ext']);
		}
		return parent::map($data);
	}
	
	public function unmap($data = array()) {
		$data = parent::unmap($data);
		if (!empty($data['ext']) && !empty($data['name'])) {
			$data['image'] = $data['name'] . '.' . $data['ext'];
		}
		return $data;
	}
	
	/**
	* Return all images for the given product
	* 
	* @param int $product_id
	* @return array
	*/
	public function getProductImages($product_id) {
		$imagesTable = self::_getTableByName(self::TABLE_NAME);
		$imagesDataset = $imagesTable->fetchAllByProductId((int)$product_id);
		return $this->getMappedArrayFromData($imagesDataset);
	}
	
	/**
	* Return one image for the given product
	* 
	* @param int $product_id
	* @param int $image_id
	* @return array
	*/
	public function getProductImageById($product_id, $image_id) {
		$imagesTable = self::_getTableByName(self::TABLE_NAME);
		$image = $imagesTable->fetchRowByProductIdAndId((int)$product_id, (int)$image_id);
		return $this->getMappedArrayFromData($image);
	}
	
	/**
	* Deletes all images for the given product
	* 
	* @param int $product_id
	* @return Model_Mapper_Db_Product
	*/
	public function deleteProductImages($product_id) {
		$imagesTable = self::_getTableByName(self::TABLE_NAME);
		$imagesTable->deleteByProductId((int)$product_id);
		return $this;
	}
}
?>
