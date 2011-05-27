<?php
/**
 * @property int $id
 * @property string $hash
 * @property string $extension
 * @property int $model_id
 * @property int $order
 */
class Model_ModelPhoto extends Model_Photo {

    const SIZE_SMALL = 'small';

	const SIZE_ROLL = 'roll';

	const SIZE_MIDDLE = 'middle';

	const SIZE_BIG = 'big';

	protected $_modelName = 'ModelPhoto';

    /**
     * @var Model_Mapper_Decorator_Cache_ModelPhoto
     */
    protected $_mapper;

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
	 * @return Model_Model
	 */
	public function getModel() {
		return Skaya_Model_Service_Abstract::factory('Model')->getModelById($this->model_id);
	}

    public function getSize($size) {
        $sizes = self::getThumbnailPack();
        return (array_key_exists($size, $sizes))?$sizes[$size]:array();
    }

    /**
     * @return Model_Mapper_Decorator_Cache_ModelPhoto
     */
    public function getMapper() {
        if (!$this->_mapper) {
            $this->_mapper = new Model_Mapper_Decorator_Cache_ModelPhoto(parent::getMapper());
        }
        return $this->_mapper;
    }

}
