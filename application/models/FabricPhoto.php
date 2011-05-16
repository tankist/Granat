<?php
class Model_FabricPhoto extends Model_Photo {

	/**
	 * @var Model_Fabric
	 */
	protected $_fabric;

	/**
	 * @throws Skaya_Model_Exception
	 * @return void
	 */
	public function getModel() {
		throw new Skaya_Model_Exception('Fabric do not belong to any model');
	}

	public function getFabric() {
		if (!$this->_fabric) {
			$this->_fabric = Skaya_Model_Service_Abstract::factory('Fabric')->getFabricById($this->fabric_id);
		}
		return $this->_fabric;
	}

}
