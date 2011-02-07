<?php
class Model_Mapper_Db_State extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'states';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	public function getStates() {
		$statesTable = self::_getTableByName(Model_Mapper_Db_State::TABLE_NAME);
		
		$statesList = $statesTable->fetchAll();
		
		return $statesList->toArray();
	}
	
	public function getStatesByCountryId($id) {
		$statesTable = self::_getTableByName(Model_Mapper_Db_State::TABLE_NAME);
		
		$statesList = $statesTable->fetchAllByCountryId($id);
		
		return $statesList->toArray();
	}
	
	public function getStateById($id) {
		$statesTable = self::_getTableByName(Model_Mapper_Db_State::TABLE_NAME);
		$statesList = $statesTable->find($id);
		if (!$statesList) {
			return false;
		}
		return $statesList->current()->toArray();
	}
	
}
?>
