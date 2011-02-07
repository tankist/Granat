<?php
class Model_Mapper_Session_ShoppingCart extends Model_Mapper_Session_Abstract {
	
	protected $_primaryKey = 'shopping_cart';
	
	public function getPrimaryKey($data = false) {
		
		return array($this->_primaryKey,'products',false, false);
	}
	
	/**
	* @desc return items from storage
	*/
	public function getData() {
		$session = $this->getSessionNamespace();
			
		$primaryKey = $this->getPrimaryKey();
		$firstKey = array_shift($primaryKey);
		$sessionData = $session->$firstKey;
		
		$_data = $sessionData;
		
		if ( is_array($_data) ) {
		
			$tmpData = array();
			$count = count($primaryKey);
			for ( $i = 0; $i < $count; $i++ ) {
				if ( $i > 0 ) {
					foreach ( $_data as $key=>$value ) {
						foreach ( $value as $v ) {
							$tmpData[] = $v;
						}
					}
				} else {
					foreach ( $_data as $key=>$value ) {
						$tmpData[] = $value;
					}	
				}
				$_data = $tmpData;
				$tmpData = array();
			}
		} 
		
	//	$_data = $this->getProductsList($_data,1,$count);
		
		return $_data;
	}
	
	function getProductsList($arr, $i, $max) {
		foreach ( $arr as $value ) {
			$res = array();
			if ( $i < $max ) {
				$res = $this->getProductsList($value, $i+1, $max);
				return $res;
			} else {
				return $res[] = $value;
			}
		}
	}
}
?>