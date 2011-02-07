<?php
class Model_Mapper_Db_User extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'Users';
	
	protected $_fieldMapping = array(
		'firstName' => 'first_name',
		'lastName' => 'last_name',
		'dateAdded' => 'date_added'
	);
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	public function unmap($data = array()) {
		$data = parent::unmap($data);
		if (array_key_exists('date_added', $data) && is_integer($data['date_added'])) {
			$data['date_added'] = new Zend_Db_Expr('FROM_UNIXTIME(' . $data['date_added'] . ')');
		}
		return $data;
	}
	
	public function map($data = array()) {
		if (array_key_exists('date_added', $data)) {
			$data['date_added'] = strtotime($data['date_added']);
		}
		return parent::map($data);
	}
	
	public function getUserById($user_id) {
		$userTable = self::_getTableByName(self::TABLE_NAME);
		$userBlob = $userTable->fetchRowById($user_id);
		return $this->getMappedArrayFromData($userBlob);
	}
	
	public function getUserByEmail($user_domain) {
		$userTable = self::_getTableByName(self::TABLE_NAME);
		$userBlob = $userTable->fetchRowByEmail($user_domain);
		return $this->getMappedArrayFromData($userBlob);
	}
}
?>
