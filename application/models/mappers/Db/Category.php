<?php
class Model_Mapper_Db_Category extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'categories';
	const PRODUCT_CATEGORIES_TABLE_NAME = 'ProductsCategories';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	protected $_fieldMapping = array(
		'displayOrder' => 'display_order'
	);
	
	/**
	* Return all categories for the given product ID
	* 
	* @param int $product_id
	* @return Zend_Db_Table_Rowset_Abstract
	*/
	public function getProductCategories($product_id) {
		$productTable = self::_getTableByName(Model_Mapper_Db_Product::TABLE_NAME);
		
		$product = $productTable->find($product_id)->current();
		if ($product instanceOf Model_Row_Abstract) {
			$categoriesDataset = $product->findManyToManyRowset('Model_DbTable_Categories', 'Model_DbTable_ProductsCategories');
			return $this->getMappedArrayFromData($categoriesDataset);
		}
		return array();
	}
	
	/**
	* Return one selected category of the given product
	* 
	* @param int $product_id
	* @param int $category_id
	* @return Zend_Db_Table_Row_Abstract
	*/
	public function getProductCategoryById($product_id, $category_id) {
		$productCategoriesTable = self::_getTableByName(self::PRODUCT_CATEGORIES_TABLE_NAME);
		
		$productCategory = $productCategoriesTable->find($category_id, $product_id)->current();
		if ($productCategory instanceOf Model_Row_Abstract) {
			$categoriesDataset = $productCategory->findParentRow('Model_DbTable_Categories');
			return $this->getMappedArrayFromData($categoriesDataset);
		}
		return array();
	}
	
	/**
	* Return one selected category of the given product identified by name
	* 
	* @param int $product_id
	* @param int $category_name
	* @return Zend_Db_Table_Rowset_Abstract
	*/
	public function getProductCategoryByName($product_id, $category_name) {
		$categoriesTable = self::_getTableByName(self::TABLE_NAME);
		$productTable = self::_getTableByName(Model_Mapper_Db_Product::TABLE_NAME);
		
		$product = $productTable->find($product_id)->current();
		if ($product instanceOf Model_Row_Abstract) {
			$categoriesDataset = $product->findManyToManyRowset('Model_DbTable_Categories', 'Model_DbTable_ProductsCategories', null, null, $categoriesTable->select()->where('name=?', $category_name)->limit(1));
			return $this->getMappedArrayFromData($categoriesDataset);
		}
		return array();
	}
	
	/**
	* Delete all categories of the selected product
	* 
	* @param int $product_id
	* @return Model_Mapper_Db_Product
	*/
	public function deleteProductCategories($product_id) {
		$productCategoriesTable = self::_getTableByName(self::PRODUCT_CATEGORIES_TABLE_NAME);
		$productCategoriesTable->deleteByProductId($product_id);
		return $this;
	}
	
	public function getStoreCategories($store_id) {
		$categoriesTable = self::_getTableByName(self::TABLE_NAME);
		
		$select = $categoriesTable->select()
			->where('store_id = ?', (int)$store_id)
			->group('id')
			->order('display_order ASC');
		
		$categoriesBlob = $categoriesTable->fetchAll($select);
		return $this->getMappedArrayFromData($categoriesBlob);
	}
	
	public function getCategoriesPaginatorByStoreId($store_id) {
		$thisTable = self::_getTableByName(self::TABLE_NAME);
		$select = $thisTable->select()->from(array('c' => $thisTable->info(Model_DbTable_Abstract::NAME)))->where('c.store_id = ?', (int)$store_id);
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $paginator;
	}
	
	public function getStoreCategoryMaximumPosition($store_id) {
		$categoriesTable = self::_getTableByName(self::TABLE_NAME);
		$select = $categoriesTable->select()
			->order('display_order DESC');
		$positionBlob = $categoriesTable->fetchRow($select);
		$positionBlob = $this->getRawArrayFromData($positionBlob);
		return (isset($positionBlob['display_order']))?$positionBlob['display_order']:0;
	}
	
	public function getStoreCategoryById($store_id, $category_id) {
		$categoriesTable = self::_getTableByName(self::TABLE_NAME);
		$categoriesBlob = $categoriesTable->fetchRowByStoreIdAndId((int)$store_id, (int)$category_id);
		return $this->getMappedArrayFromData($categoriesBlob);
	}
	
	public function getStoreCategoryByName($store_id, $category_name) {
		$categoriesTable = self::_getTableByName(self::TABLE_NAME);
		$categoriesBlob = $categoriesTable->fetchRowByStoreIdAndName((int)$store_id, $category_name);
		return $this->getMappedArrayFromData($categoriesBlob);
	}
	
	public function save($data) {
		$data = $this->unmap($data);
		if (isset($data['id'])) {
			if (!isset($data['display_order'])) {
				//Saving existing category and display_order isn't existing - generate new one
				$data['display_order'] = new Zend_Db_Expr('IF (display_order = 0, `getNextDisplayOrderForStore`(store_id), display_order)');
			}
		}
		elseif (!empty($data['store_id'])) {
			//Inserting new record - simply generate new display_order
			$data['display_order'] = new Zend_Db_Expr('`getNextDisplayOrderForStore`(' . $data['store_id'] . ')');
		}
		$category = parent::save($data);
		if (($product_id = (int)$data['product_id']) && !empty($category['id'])) {
			$productCategoriesTable = self::_getTableByName(self::PRODUCT_CATEGORIES_TABLE_NAME);
			$productCategory = $productCategoriesTable->find($category['id'], $product_id)->current();
			if (!$productCategory) {
				$productCategory = $productCategoriesTable->createRow(array(
					'category_id' => $category['id'],
					'product_id' => $product_id
				));
				$productCategory->save();
			}
		}
		return $category;
	}
	
}
?>
