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
				$filename = $modifier . $this->hash . '.' . $this->extension;
				break;
			case self::INDICATION_SUFFIX:
				$filename = $this->hash . $modifier . '.' . $this->extension;
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
	 * @param  int $size
	 * @return Model_Photo
	 */
	public function setFilename($filename, $size = null) {
		$this->hash = pathinfo($filename, PATHINFO_FILENAME);
        $this->extension = pathinfo($filename, PATHINFO_EXTENSION);
        if ($size && array_key_exists($size, $this->getThumbnailPack())) {
            $tp = $this->getThumbnailPack();
            $this->hash = str_replace($tp[$size]['indication'], '', $this->hash);
        }
		return $this;
	}

    public function getSize($size) {
        $sizes = self::getThumbnailPack();
        return (array_key_exists($size, $sizes))?$sizes[$size]:array();
    }

}
