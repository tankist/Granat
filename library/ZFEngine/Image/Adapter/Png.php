<?php
/**
 * ZFEngine
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://zfengine.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zfengine.com so we can send you a copy immediately.
 *
 * @category   ZFEngine
 * @package    ZFEngine_Image
 * @copyright  Copyright (c) 2009-2010 Stepan Tanasiychuk (http://stfalcon.com)
 * @license    http://zfengine.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Адаптер для работы с png
 *
 * @category   ZFEngine
 * @package    ZFEngine_Image
 * @copyright  Copyright (c) 2009-2010 Stepan Tanasiychuk (http://stfalcon.com)
 * @license    http://zfengine.com/license/new-bsd     New BSD License
 */
class ZFEngine_Image_Adapter_Png extends ZFEngine_Image_Adapter_Abstract
{
    /**
     * Конструктор. Открывает файл изображения
     *
     * @param string $filename
     * @return ZFEngine_Image_Adapter_Png
     */
    public function  __construct($filename)
    {
        $this->_filename = $filename;
        $this->_image = imagecreatefrompng($this->_filename);

        imagealphablending($this->_image, false);
        imagesavealpha($this->_image, true);

        return $this;
    }

    /**
     * Показать изображение в браузере
     *
     * @param integer $quality  Compression level: from 0 (no compression) to 9
     * @return void
     */
    public function show($quality = 75)
    {
        header('Content-type: image/png');

        imagepng($this->_image, null, self::_quality($quality));
    }

    /**
     * Сохранить изображение в тот же файл
     *
     * @param integer $quality
     * @throws ZFEngine_Image_Exception
     * @return ZFEngine_Image_Adapter_Png
     */
    public function save($quality = 95)
    {
        $result = imagepng($this->_image, $this->_filename, self::_quality($quality));

        if ($result === true) {
            return $this;
        }

        require_once 'ZFEngine/Image/Adapter/Exception.php';
        throw new ZFEngine_Image_Adapter_Exception(sprintf("File '%s' could not be
                                        saved. An error occured while
                                        processing the file.", $this->_filename));
    }

    /**
     * Перевод процентной велечины качества в уровень компресии для png
     *
     * @param integer $quality
     * @return integer
     */
    private static function _quality($quality) {
        return 9 - round($quality * 9 / 100);
    }

    /**
     * Создает "холст" для результирующего изображения
     *
     * @param integer $width
     * @param integer $height
     * @return resource
     */
    protected function _imageCreate($width, $height)
    {
        if (function_exists('imagecreatetruecolor')) {
            $image = imagecreatetruecolor($width, $height);

            imagealphablending($image, false);
            imagesavealpha($image, true);

            // Create a new transparent color for image
            $bgcolor = imagecolorallocatealpha($image, 0, 0, 0, 127);
            // Completely fill the background of the new image with allocated color.
            imagefilledrectangle($image, 0, 0, $width, $width, $bgcolor);
            //imagefill($image, 0, 0, $bgcolor);
        } else {
            $image = imagecreate($width, $height);
        }

        return $image;
    }

}
