<?php
class Model_Mapper_Db_Country extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'countries';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	public function getCountries() {
		$countriesTable = self::_getTableByName(self::TABLE_NAME);
		$countriesList = $countriesTable->fetchAll();
		return $this->getMappedArrayFromData($countriesList);
	}
	
	public function getAvailableCountries() {
		$countriesTable = self::_getTableByName(self::TABLE_NAME);
		$countriesList = $countriesTable->fetchAllByAvailable(1,array('id desc'));
		return $this->getMappedArrayFromData($countriesList);
	}
	
	public function getCountryById($country_id) {
		$countriesTable = self::_getTableByName(self::TABLE_NAME);
		$country = $countriesTable->fetchRowById($country_id);
		return $this->getMappedArrayFromData($country);
	}
	
}
?>
