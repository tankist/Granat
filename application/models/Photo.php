<?php
/**
 * @property int $id
 * @property string $hash
 * @property string $extension
 */
class Model_Photo extends Skaya_Model_Abstract {

	const INDICATION_SUFFIX = 'suffix';

	const INDICATION_PREFIX = 'prefix';

	protected static $_thumbnailPack = array();

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
		$sizes = $this->getSize($size);
		if (!empty($size)) {
			$modifier = $sizes['indication'];
			$indicationType = $sizes['indication_type'];
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

    public function getSize($size) {
        $sizes = self::getThumbnailPack();
        return (array_key_exists($size, $sizes))?$sizes[$size]:array();
    }

}
