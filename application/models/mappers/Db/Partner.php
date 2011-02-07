<?php
class Model_Mapper_Db_Partner extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'partners';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	
	public function getDomainsByPartnerId($partner_id) {
		$partnerTable = self::_getTableByName('stores');
		$stores = $partnerTable->fetchAllByPartnerId((int)$partner_id);
		$domains = array();
		foreach ($stores as $store) {
			$domains[] = $store['domain'];
		}
		
		return $domains;
	}
	
	public function getPartnerById($partner_id) {
		$partnerTable = self::_getTableByName(self::TABLE_NAME);
		$partnerInfo = array();
		if ($partner_id > 0) {
			$partnerBlob = $partnerTable->find($partner_id);
			if ($partnerBlob) {
				$partnerInfo = $partnerBlob->current()->toArray();
			}
		}
		return $partnerInfo;
	}
	
	public function getPartnerByApiKey($apiKey) {
		$partnerTable = self::_getTableByName(self::TABLE_NAME);
		$partnerBlob = $partnerTable->fetchRowByApiKey($apiKey);
		
		return $partnerBlob;
	}
}
?>
