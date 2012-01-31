<?php

namespace Entities\Model;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="model_photos")
 */
class Photo extends \Entities\AbstractPhoto
{

    const THUMBNAIL_SMALL = 'small';

    const THUMBNAIL_ROLL = 'roll';

    const THUMBNAIL_MEDIUM = 'medium';

    const THUMBNAIL_BIG = 'big';

    public static $thumbSettings = array(
        self::THUMBNAIL_SMALL => array(
            'width' => 84,
            'height' => 90,
            'saveProportions' => false,
            'suffix' => '_s',
            'empty' => '/img/84x90.png'
        ),
        self::THUMBNAIL_ROLL => array(
            'width' => 139,
            'height' => 155,
            'saveProportions' => false,
            'suffix' => '_r',
            'empty' => '/img/60x60.png'
        ),
        self::THUMBNAIL_MEDIUM => array(
            'width' => 250,
            'height' => 250,
            'saveProportions' => false,
            'suffix' => '_m',
            'empty' => '/img/100x100.png'
        ),
        self::THUMBNAIL_BIG => array(
            'width' => 214,
            'height' => 286,
            'saveProportions' => false,
            'suffix' => '_b',
            'empty' => '/img/120x120.png'
        )
    );


    /**
     * @var \Entities\Model
     * @ORM\ManyToOne(targetEntity="Entities\Model", inversedBy="photos")
     */
    protected $model;

    /**
     * @param \Entities\Model $model
     * @return \Entities\Model\Photo
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return \Entities\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return \Entities\Attachable
     */
    public function getContainerEntity()
    {
        return $this->getModel();
    }

    /**
     * @param $size
     * @return array
     */
    public function getThumbSetting($size)
    {
        if (array_key_exists($size, self::$thumbSettings)) {
            return self::$thumbSettings[$size];
        }
        return false;
    }
}
