<?php

class Service_FabricPhoto extends Skaya_Model_Service_Abstract {

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_FabricPhoto($data);
	}

}