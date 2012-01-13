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
 * Адаптер для работы с gif
 *
 * @category   ZFEngine
 * @package    ZFEngine_Image
 * @copyright  Copyright (c) 2009-2010 Stepan Tanasiychuk (http://stfalcon.com)
 * @license    http://zfengine.com/license/new-bsd     New BSD License
 */
class ZFEngine_Image_Adapter_Gif extends ZFEngine_Image_Adapter_Abstract
{
    /**
     * Конструктор. Открывает файл изображения
     *
     * @param string $filename
     * @return ZFEngine_Image_Adapter_Gif
     */
    public function  __construct($filename)
    {
        $this->_filename = $filename;
        $this->_image = imagecreatefromgif($this->_filename);

        return $this;
    }

    /**
     * Показать изображение в браузере
     *
     * @return void
     */
    public function show($quality = null)
    {
        header('Content-type: image/gif');

        imagegif($this->_image);
    }

    /**
     * Сохранить изображение в тот же файл
     *
     * @throws ZFEngine_Image_Exception
     * @return ZFEngine_Image_Adapter_Gif
     */
    public function save($quality = null)
    {
        $result = imagegif($this->_image, $this->_filename);

        if ($result === true) {
            return $this;
        }

        require_once 'ZFEngine/Image/Adapter/Exception.php';
        throw new ZFEngine_Image_Adapter_Exception(sprintf("File '%s' could not be
                                        saved. An error occured while
                                        processing the file.", $this->_filename));
    }
}
