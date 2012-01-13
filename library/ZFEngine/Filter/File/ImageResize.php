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
 * @package    ZFEngine_Filter
 * @subpackage File
 * @copyright  Copyright (c) 2009-2010 Stepan Tanasiychuk (http://stfalcon.com)
 * @license    http://zfengine.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Filter for resize image
 *
 * @category   ZFEngine
 * @package    ZFEngine_Filter
 * @subpackage File
 * @copyright  Copyright (c) 2009-2010 Stepan Tanasiychuk (http://stfalcon.com)
 * @license    http://zfengine.com/license/new-bsd     New BSD License
 */
class ZFEngine_Filter_File_ImageResize implements Zend_Filter_Interface
{
    /**
     * Options
     * @var array
     */
    private $_options = array('width' => 0,
        'height' => 0,
        'quality' => 100,
        'saveProportions' => false);

    /**
     * Set options
     *
     * @param array|string|Zend_Config $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif (is_int($options)) {
            $options = array('width' => $options);
        } elseif (!is_array($options)) {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Invalid options argument provided to filter');
        }

        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * Resize image
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        if (!file_exists($value)) {
            return $value;
        }

        $image = ZFEngine_Image::factory($value);
        $image->resize($this->_options['width'], $this->_options['height'], $this->_options['saveProportions'])
            ->save();

        return $value;
    }
}
