<?php
class Model_Mapper_Db_Store extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'stores';
	
	protected $_fieldMapping = array(
		'password' => 'passwd',
		'lastLoginDate' => 'last_login_date',
	);
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	public function unmap($data = array()) {
		$data = parent::unmap($data);
		if (array_key_exists('last_login_date', $data) && is_integer($data['last_login_date'])) {
			$data['last_login_date'] = new Zend_Db_Expr('FROM_UNIXTIME(' . $data['last_login_date'] . ')');
		}
		if (array_key_exists('payout_info',$data) && !is_string($data['payout_info'])) {
			$data['payout_info'] = serialize($data['payout_info']);
		}
		return $data;
	}
	
	public function map($data = array()) {
		if (array_key_exists('last_login_date', $data)) {
			$data['last_login_date'] = strtotime($data['last_login_date']);
		}
		if (array_key_exists('payout_info', $data) && is_string($data['payout_info'])) {
			$data['payout_info'] = unserialize($data['payout_info']);
		}
		return parent::map($data);
	}
	
	public function getStoreById($store_id) {
		$storeTable = self::_getTableByName(self::TABLE_NAME);
		$storeBlob = $storeTable->fetchRowById($store_id);
		return $this->getMappedArrayFromData($storeBlob);
	}
	
	public function getStoreByDomain($store_domain) {
		$storeTable = self::_getTableByName(self::TABLE_NAME);
		$storeBlob = $storeTable->fetchRowByDomain($store_domain);
		return $this->getMappedArrayFromData($storeBlob);
	}
}
?>
