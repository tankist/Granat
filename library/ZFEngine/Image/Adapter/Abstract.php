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
 * Класc для создания изображения и выполнения различных действий над ним
 *
 * @category   ZFEngine
 * @package    ZFEngine_Image
 * @copyright  Copyright (c) 2009-2010 Stepan Tanasiychuk (http://stfalcon.com)
 * @license    http://zfengine.com/license/new-bsd     New BSD License
 */
abstract class ZFEngine_Image_Adapter_Abstract
{
        // Master Dimension
        const NONE = 1;
        const AUTO = 2;
        const HEIGHT = 3;
        const WIDTH = 4;


    /**
     * Файл изображения
     * @var resource
     */
    protected $_filename;

    /**
     * Ресурс изображения
     * @var resource
     */
    protected $_image;


    /**
     * Конструктор. Открывает файл изображения
     * 
     * @param string $filename
     * @return ZFEngine_Image_Adapter_Abstract
     */
    abstract public function  __construct($filename);

    /**
     * Деструктор освобождает ресурсы выделенные под изображение
     *
     * @return void
     */
    public function  __destruct()
    {
        imagedestroy($this->_image);
    }

    /**
     * Показать изображение в браузере
     *
     * @param integer $quality
     * @return ZFEngine_Image_Adapter_Abstract
     */
    abstract public function show($quality = 75);

    /**
     * Сохранить изображение в тот же файл
     *
     * @param integer $quality
     * @return ZFEngine_Image_Adapter_Abstract
     */
    abstract public function save($quality = 75);
    
    /**
     * Сохранить изображение в другой файл
     *
     * @param string $filename
     * @param string $suffix
     * @param integer $quality
     * @return ZFEngine_Image_Adapter_Abstract
     */
    public function saveAs($filename, $quality = 75)
    {
        $this->_filename = $filename;
        $this->save($quality);

        return $this;
    }

    /**
     * Изменить размер изображения
     *
     * @param integer $maxWidth
     * @param integer $maxHeight
     * @param boolean $saveProportions
     * @param $ratioType
     * @return ZFEngine_Image_Adapter_Abstract
     */
    public function resize($maxWidth, $maxHeight = 0, $saveProportions = false, $ratioType = NULL)
    {
        if (min($maxWidth, $maxHeight) < 0) {
            throw new Exception('Неккоректные размеры результирующего изображения');
        }

        // если картинка меньше чем размер до которого нужно заресайзить, тогда не ресайзим
        if (($maxWidth && $maxHeight) && ($this->getWidth() <= $maxWidth && $this->getHeight() <= $maxHeight)) {
            return $this;
        }

        if ($maxHeight == 0) {
            $saveProportions = true;
        }

        if ($saveProportions) {
            /*
             * Сохраняя пропорции оригинального изображения
             * вычисляем размеры результирующего изображения
             */

            /*
             * Выбираем больший коефициент соотношения сторон оригинального
             * и результирующего изображения
             */

            $ratioWidth = $this->getWidth() / $maxWidth;
            $ratioHeight = ($maxHeight > 0) ? ($this->getHeight() / $maxHeight) : $ratioWidth;
            switch ($ratioType) {
                case self::HEIGHT:
                    $ratio = $ratioHeight;
                    break;
                case self::WIDTH:
                    $ratio = $ratioWidth;
                    break;
                case self::AUTO:
                default:
                    $ratio = max($ratioWidth, $ratioHeight);
                    break;
            }

            // Вычисляем ширину и высоту результирующего изображения
            $dstWidth = round($this->getWidth() / $ratio);
            $dstHeight = round($this->getHeight() / $ratio);

            // Копируем всю область оригинального изображения
            $srcWidth = $this->getWidth();
            $srcHeight = $this->getHeight();

            // Копируем область от верхнего левого узла
            $srcX = 0;
            $srcY = 0;
        } else {
            /* Не сохраняя поропорции оригинального изображения
             * вычисляем размер области оригинального изображения которую
             * будем копировать и изменять размер
             */

            /*
             * Выбираем меньший коефициент соотношения сторон оригинального
             * и результирующего изображения
             */
            $ratio = min(($this->getWidth() / $maxWidth),
                         ($this->getHeight() / $maxHeight));

            // Ширина и высота результирующего изображения
            $dstWidth = $maxWidth;
            $dstHeight = $maxHeight;
            
            /*
             * Вычисляем размеры области оригинального изображения
             * которую будем копировать
             */
            $srcWidth = round($maxWidth * $ratio);
            $srcHeight = round($maxHeight * $ratio);

            /**
             * Расчитываем координаты левого верхнего узла
             * так чтобы скопировать центральную часть оригинального изображения
             */
            $srcX = round(($this->getWidth() - $srcWidth) / 2);
            //$srcY = round(($this->getHeight() - $srcHeight) / 2);
            $srcY = 0;
        }

        /**
         * Меняем размер изображения
         */

        /**
         * Создаем "холст" для результирующего изображения
         */
        $image = $this->_imageCreate($dstWidth, $dstHeight);

        /**
         * Копируем копию изображения с измененными размерами
         * на подготовленный "холст"
         */
        imagecopyresampled($image, $this->_image,
                           0, 0,
                           $srcX, $srcY,
                           $dstWidth, $dstHeight,
                           $srcWidth, $srcHeight);

        $this->_image = $image;

        return $this;
    }

    /**
     * Повернуть изображение
     *
     * @param integer $degrees
     * @return ZFEngine_Image_Adapter_Abstract
     */
    public function rotate($degrees)
    {
        $this->_image = imagerotate($this->_image, $degrees, -1);

         // сохраняем прозрачность для gif и png
        imagealphablending($this->_image, true);
        imagesavealpha($this->_image, true);

        return $this;
    }

    /**
     * Ширина изображения
     * 
     * @return integer 
     */
    public function getWidth()
    {
        return imagesx($this->_image);
    }
    
    /**
     * Высота изображения
     *
     * @return integer
     */
    public function getHeight()
    {
        return imagesy($this->_image);
    }

    /**
     * Черно-белый фильтр
     *
     * @return ZFEngine_Image_Adapter_Abstract
     */
    public function filterGrayscale()
    {
        $width    = $this->getWidth();
        $height   = $this->getHeight();

        $image = imagecreate($width, $height);

        for ($c = 0; $c < 256; $c++) {
            imagecolorallocate($image, $c, $c, $c);
        }

        imagecopymerge($image, $this->_image,
                       0, 0, 0, 0,
                       $width, $height, 100);

        $this->_image = $image;

        return $this;
    }

    /**
     * Возвращает имя файла с изображением
     *
     * @return string
     */
    public function getFileName() {
        return basename($this->getFullPath());
    }

    /**
     * Возвращает полный путь к файлу изображения
     *
     * @return string
     */
    public function getFullPath() {
        return $this->_filename;
    }

    /**
     * Добавить водяной знак
     *
     * @return ZFEngine_Image_Adapter_Abstract
     */
    public function addWatermark($watermarkFileName, $rate = 0.05)
    {
        $watermark = ZFEngine_Image::factory($watermarkFileName);
        $watermark->resize($this->getWidth() * $rate, $this->getHeight() * $rate, true, 100);

        $startX = ($this->getWidth() - 5) - $watermark->getWidth();
        $startY = ($this->getHeight() - 5) - $watermark->getHeight();
        imagecopy($this->_image, $watermark->getImageResourse(),
                  $startX, $startY,
                  0, 0,
                  $watermark->getWidth(), $watermark->getHeight());

        return $this;
    }

    public function getImageResourse() {
        return $this->_image;
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
            $bgcolor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $bgcolor);
        } else {
            $image = imagecreate($width, $height);
        }

        return $image;
    }

}