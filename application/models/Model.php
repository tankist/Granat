<?php

class Model_Model extends Skaya_Model_Abstract {

	protected $_modelName = 'Model';

	protected static $_thumbnailPack = array(
		'small' => array(
			'width' => 175,
			'height' => 175,
			'indication_type' => 'suffix',
			'indication' => 's'
		),
		'middle' => array(
			'width' => 135,
			'height' => 135,
			'indication_type' => 'suffix',
			'indication' => 'm'
		),
		'big' => array(
			'width' => 300,
			'height' => 300,
			'indication_type' => 'suffix',
			'indication' => 'b'
		)
	);

	public static function setThumbnailPack($thumbnailPack) {
		self::$_thumbnailPack = $thumbnailPack;
	}

	public static function getThumbnailPack() {
		return self::$_thumbnailPack;
	}

}