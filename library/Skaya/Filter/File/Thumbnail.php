<?php

/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Skaya_Filter_File_Thumbnail implements Zend_Filter_Interface
{
    /**
     * Internal array of array(source, target, overwrite)
     */
    protected $_options = array();

    /**
     * Class constructor
     *
     * Options argument may be either a string, a Zend_Config object, or an array.
     * If an array or Zend_Config object, it accepts the following keys:
     * 'source'    => Source filename or directory which will be renamed
     * 'target'    => Target filename or directory, the new name of the sourcefile
     * 'overwrite' => Shall existing files be overwritten ?
     *
     * @param  string|array $options Target file or directory to be renamed
     * @param  string $target Source filename or directory (deprecated)
     * @param  bool $overwrite Should existing files be overwritten (deprecated)
     * @return void
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif (!is_array($options)) {
            throw new Zend_Filter_Exception('Invalid options argument provided to filter');
        }


        $this->setOptions($options);
    }

    /**
     * Returns the files to rename and their new name and location
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Sets a new file or directory as target, deleting existing ones
     *
     * Array accepts the following keys:
     * 'source'    => Source filename or directory which will be renamed
     * 'target'    => Target filename or directory, the new name of the sourcefile
     * 'overwrite' => Shall existing files be overwritten ?
     *
     * @param  string|array $options Old file or directory to be rewritten
     * @return Zend_Filter_File_Rename
     */
    public function setOptions($options)
    {
        $this->_options = $options;

        return $this;
    }

    /**
     * Returns only the new filename without moving it
     * But existing files will be erased when the overwrite option is true
     *
     * @param  string  $value  Full path of file to change
     * @param  boolean $source Return internal informations
     * @return string The new filename which has been set
     */
    public function getNewName($value, $option)
    {
        $dir = dirname($value);
        $ext = substr($value, strrpos($value, '.'));
        $name = basename($value, $ext);

        if ($option['indication_type'] == 'suffix') {
            $name = $name . '_' . $option['indication'];
        } else {
            $name = $option['indication'] . '_' . $name;
        }

        $file = $dir . '/' . $name . $ext;

        return $file;
    }

    /**
     * Defined by Zend_Filter_Interface
     *
     * Renames the file $value to the new name set before
     * Returns the file $value, removing all but digit characters
     *
     * @param  string $value Full path of file to change
     * @throws Zend_Filter_Exception
     * @return string The new filename which has been set, or false when there were errors
     */
    public function filter($value)
    {
        $options = $this->getOptions();
        foreach ($options as $thumb_name => $option) {
            $thumb_name = $this->getNewName($value, $option);

            $image = new Imagick($value);
            $geo = $image->getImageGeometry();
            $width = $geo['width'];
            $height = $geo['height'];
            $newWidth = $option['width'];
            $newHeight = $option['height'];

            $widthFactor = $width / $newWidth;
            $heightFactor = $height / $newHeight;

            if ($widthFactor < $heightFactor) {
                $image->cropImage($width, floor($newHeight * $widthFactor), 0, 0 /*(($height-($newHeight*$widthFactor))/2)*/);
            }
            else {
                $image->cropImage(ceil($newWidth * $heightFactor), $height, (($width - ($newWidth * $heightFactor)) / 2), 0);
            }
            $image->thumbnailimage($newWidth, $newHeight, true);
            $image->writeImage($thumb_name);
        }


        return $value;

    }


}
