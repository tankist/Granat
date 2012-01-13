<?php

/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * Класс для работы с изображениями
 *
 * @category   ZFEngine
 * @package    ZFEngine_Image
 * @author     Stepan Tanasiychuk (http://stfalcon.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZFEngine_Image
{
    /**
     * Фабрика для классов ZFEngine_Image_Adapter_Abstract
     *
     * @param string $filename
     * @throws ZFEngine_Image_Exception
     * @return ZFEngine_Image_Adapter_Abstract
     */
    public static function factory($filename)
    {
        // make sure the GD library is installed
        if (!function_exists('gd_info')) {
            require_once 'ZFEngine/Image/Exception.php';
            throw new ZFEngine_Image_Exception('You do not have the GD Library installed');
        }

        /**
         * Проверяем изображение
         */
        $size = getimagesize($filename);
        if ($size === false) {
            require_once 'ZFEngine/Image/Exception.php';
            throw new ZFEngine_Image_Exception(sprintf('Изображение \'%s\' повреждено',
                $filename));
        }

        /*
         * В зависимости от Mime-Type выбираем подходящий
         * адаптер для работы с изображением
         */
        switch ($size['mime']) {
            case 'image/jpeg':
                $adapterName = 'ZFEngine_Image_Adapter_Jpeg';
                break;

            case 'image/gif':
                $adapterName = 'ZFEngine_Image_Adapter_Gif';
                break;

            case 'image/png':
                $adapterName = 'ZFEngine_Image_Adapter_Png';
                break;

            default:
                require_once 'ZFEngine/Image/Exception.php';
                throw new ZFEngine_Image_Exception('Этот тип изображений не
                                              поддерживается');
                break;
        }

        /*
         * Подгружаем класс адаптера. Бросает исключение если
         * класс не может быть загружен
         */
        Zend_Loader::loadClass($adapterName);

        /**
         * Создаем экземпляр класса адаптера. В конструктор адаптера
         * передается путь к файлу изображения
         */
        $imageAdapter = new $adapterName($filename);

        if (!$imageAdapter instanceof ZFEngine_Image_Adapter_Abstract) {
            require_once 'ZFEngine/Image/Exception.php';
            throw new ZFEngine_Image_Exception(sprintf('Класс адаптера \'%s\' не
                                                   наследует ZFEngine_Image_Adapter_Abstract'));
        }

        return $imageAdapter;
    }
}
