<?php

class Model_Mapper_Db_User extends Skaya_Model_Mapper_Db_Abstract {

	const TABLE_NAME = 'Users';

	protected $_fieldMapping = array(
		'firstName' => 'first_name',
		'lastName' => 'last_name',
		'dateAdded' => 'date_added'
	);

	protected $_mapperTableName = 'Users';

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

	public function getUserById($id) {
		$userTable = self::_getTableByName(self::TABLE_NAME);
		$userBlob = $userTable->fetchRowById($id);
		return $this->getMappedArrayFromData($userBlob);
	}

	public function getUsers($order = null, $count = null, $offset = null) {
		$userTable = self::_getTableByName(self::TABLE_NAME);
		$userBlob = $userTable->fetchAll(null, $order, $count, $offset);
		return $this->getMappedArrayFromData($userBlob);
	}

	public function getUsersPaginator($order = null) {
		$userTable = self::_getTableByName(self::TABLE_NAME);
		$select = $userTable->select();
		if ($order) {
			$select->order($this->_mapOrderStatement($order));
		}
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $paginator;
	}

	public function getUserByEmail($email) {
		$userTable = self::_getTableByName(self::TABLE_NAME);
		$userBlob = $userTable->fetchRowByEmail($email);
		return $this->getMappedArrayFromData($userBlob);
	}

}