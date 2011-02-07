<?php
class Model_Mapper_Session_ShoppingCartProduct extends Model_Mapper_Session_Abstract {
	
	protected $_primaryKey = 'shopping_cart';
	
	protected $_fieldMapping = array(
		'id' => 'product_id',
		'name' => 'product_name',
		'description' => 'product_desc',
		'price' => 'product_price'
	);
	
	public function map($data = array()) {
		if (isset($data['product_info']) && is_string($data['product_info'])) {
			$data['product_info'] = unserialize($data['product_info']);
		}
		if (isset($data['shipping_info']) && is_string($data['shipping_info'])) {
			$data['shipping_info'] = unserialize($data['shipping_info']);
		}
		return parent::map($data);
	}
	
	public function unmap($data = array()) {
		$data = parent::unmap($data);
		if (isset($data['product_info']) && !is_string($data['product_info'])) {
			$data['product_info'] = serialize($data['product_info']);
		}
		if (isset($data['shipping_info']) && !is_string($data['shipping_info'])) {
			$data['shipping_info'] = serialize($data['shipping_info']);
		}
		return $data;
	}
	
	public function getPrimaryKey($data = false) {
		return array($this->_primaryKey,'products',$data['id'],$data['product_info']['id']);
	}
	
	/**
	* Delete item from session store
	* 
	* @param array $data
	* @return int
	*/
	public function delete($data) {
		if ( is_array($data) ) {
			$session = $this->getSessionNamespace();
			
			$primaryKey = $this->getPrimaryKey($data);
			$firstKey = array_shift($primaryKey);
			$lastKey = array_pop($primaryKey);
			$sessionData = $session->$firstKey;
			
			$_data = &$sessionData;

			foreach ($primaryKey as $key) {
				$_data = &$_data[$key];
			}
			if ( $_data[$lastKey] && $_data[$lastKey]['product_info'] == $data['product_info'] ) {
				unset($_data[$lastKey]);
			}

			$session->$firstKey = $sessionData;
		}
		
		return true;
	}
}
?>