<?php
class Model_FabricPhoto extends Model_Photo {

	const SIZE_FABRIC = 'fabric';

	protected static $_thumbnailPack = array(
		self::SIZE_FABRIC => array(
			'width' => 150,
			'height' => 150,
			'indication_type' => self::INDICATION_SUFFIX,
			'indication' => 'f'
		)
	);

	/**
	 * @var Model_Fabric
	 */
	protected $_fabric;

	/**
	 * @static
	 * @param  $thumbnailPack
	 * @return void
	 */
	public static function setThumbnailPack($thumbnailPack) {
		self::$_thumbnailPack = $thumbnailPack;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getThumbnailPack() {
		return self::$_thumbnailPack;
	}

	public function getFabric() {
		if (!$this->_fabric) {
			$this->_fabric = Skaya_Model_Service_Abstract::factory('Fabric')->getFabricById($this->fabric_id);
		}
		return $this->_fabric;
	}

    public function getSize($size) {
        $sizes = self::getThumbnailPack();
        return (array_key_exists($size, $sizes))?$sizes[$size]:array();
    }

}
