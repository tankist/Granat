<?php
class Model_Mapper_Rest_Abstract extends Model_Mapper_Abstract {
	
	public function save($data) {
		throw new Model_Mapper_Rest_Exception('This method is not implemented for REST api');
	}
	
	public function delete($data) {
		throw new Model_Mapper_Rest_Exception('This method is not implemented for REST api');
	}
	
	public function search($conditions, $order = null, $count = null, $offset = null) {
		throw new Model_Mapper_Rest_Exception('This method is not implemented for REST api');
	}
}
?>
