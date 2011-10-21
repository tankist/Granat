<?php

class Sch_Filter_ThumbFilename implements Zend_Filter_Interface
{

    /**
     * @var string
     */
    protected $_prefix = '';

    /**
     * @var string
     */
    protected $_suffix = '';

    /**
     * @var string
     */
    protected $_filename = '';

    /**
     * @var string
     */
    protected $_extension = '';

    public function __construct($prefix = '', $suffix = '')
    {
        $this->setPrefix($prefix)->setSuffix($suffix);
    }

    public function filter($value)
    {
        $pathinfo = pathinfo($value);
        $dirname = ($pathinfo['dirname'] == '.')?'':$pathinfo['dirname'] . DIRECTORY_SEPARATOR;
        $filename = ($this->getFilename())?$this->getFilename():$pathinfo['filename'];
        $extension = ($this->getExtension())?$this->getExtension():$pathinfo['extension'];
        if ($prefix = $this->getPrefix()) {
            $filename = $prefix . $filename;
        }
        if ($suffix = $this->getSuffix()) {
            $filename .= $suffix;
        }
        return $dirname . $filename . '.' . $extension;
    }

    /**
     * @param  $prefix
     * @return Sch_Filter_ThumbFilename
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * @param  $suffix
     * @return Sch_Filter_ThumbFilename
     */
    public function setSuffix($suffix)
    {
        $this->_suffix = $suffix;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->_suffix;
    }

    /**
     * @param string $filename
     * @return self
     */
    public function setFilename($filename)
    {
        $this->_filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * @param string $extension
     * @return self
     */
    public function setExtension($extension)
    {
        $this->_extension = $extension;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->_extension;
    }

}
