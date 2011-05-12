<?php
/**
 * @property int $id
 * @property string $hash
 * @property string $extension
 * @property int $model_id
 * @property int $order
 */
class Model_Photo extends Skaya_Model_Abstract {

	const SIZE_SMALL = 'small';

	const SIZE_ROLL = 'roll';

	const SIZE_MIDDLE = 'middle';

	const SIZE_BIG = 'big';

	const SIZE_FABRIC = 'fabric';

	const INDICATION_SUFFIX = 'suffix';

	const INDICATION_PREFIX = 'prefix';

	protected $_modelName = 'Photo';

	protected static $_thumbnailPack = array(
		self::SIZE_SMALL => array(
			'width' => 84,
			'height' => 90,
			'indication_type' => self::INDICATION_SUFFIX,
			'indication' => 's'
		),
		self::SIZE_ROLL => array(
			'width' => 142,
			'height' => 105,
			'indication_type' => self::INDICATION_SUFFIX,
			'indication' => 'r'
		),
		self::SIZE_MIDDLE => array(
			'width' => 250,
			'height' => 250,
			'indication_type' => self::INDICATION_SUFFIX,
			'indication' => 'm'
		),
		self::SIZE_BIG => array(
			'width' => 214,
			'height' => 286,
			'indication_type' => self::INDICATION_SUFFIX,
			'indication' => 'b'
		),
		self::SIZE_FABRIC => array(
			'width' => 150,
			'height' => 150,
			'indication_type' => self::INDICATION_SUFFIX,
			'indication' => 'f'
		)
	);

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

	/**
	 * @param string $size
	 * @return string
	 */
	public function getFilename($size = null) {
		$modifier = $indicationType = '';
		$sizes = self::getThumbnailPack();
		if ($size !== null && array_key_exists($size, $sizes)) {
			$modifier = $sizes[$size]['indication'];
			$indicationType = $sizes[$size]['indication_type'];
		}
		switch ($indicationType) {
			case self::INDICATION_PREFIX:
				$filename = $modifier . '_' . $this->hash . '.' . $this->extension;
				break;
			case self::INDICATION_SUFFIX:
				$filename = $this->hash . '_' . $modifier . '.' . $this->extension;
				break;
			default:
				$filename = $this->hash . '.' . $this->extension;
				break;
		}
		return $filename;
	}

	/**
	 * @throws Skaya_Model_Exception
	 * @param  string $filename
	 * @return Model_Photo
	 */
	public function setFilename($filename) {
		if ($dotPos = strrpos($filename, '.')) {
			$this->hash = substr($filename, 0, $dotPos);
			$this->extension = substr($filename, $dotPos + 1);
		}
		else {
			throw new Skaya_Model_Exception('Incorrect filename passed');
		}
		return $this;
	}

	/**
	 * @return Model_Model
	 */
	public function getModel() {
		return Skaya_Model_Service_Abstract::factory('Model')->getModelById($this->model_id);
	}

}
