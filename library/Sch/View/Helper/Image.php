<?php

class Sch_View_Helper_Image extends Zend_View_Helper_Abstract
{

    private $_manager;

    private $_filePath;

    public function image($path = null)
    {
        if (null != $path) {
            $this->_filePath = $path;
        }
        return $this;
    }

    public function thumb($width, $height, $saveProportions = false)
    {
        $fullFilePath = $this->_getManager()->getPath($this->_filePath);
        $thumbFilenameFilter = new Sch_Filter_ThumbFilename(null, '_' . $width . 'x' . $height);
        $thumbFilename = $thumbFilenameFilter->filter($fullFilePath);
        if (!file_exists($thumbFilename)) {
            copy($fullFilePath, $thumbFilename);
            $filter = new ZFEngine_Filter_File_ImageResize(array(
                'width' => $width,
                'height' => $height,
            ));
            $filter->filter($thumbFilename);
        }
        $thumbFilename = dirname($this->_filePath) . DIRECTORY_SEPARATOR . basename($thumbFilename);
        return $this->_manager->getUrl($thumbFilename);
    }

    public function full()
    {
        return $this->_manager->getUrl($this->_filePath);
    }

    public function setManager($manager)
    {
        $this->_manager = $manager;
        return $this;
    }

    private function _getManager()
    {
        if (!$this->_manager) {
            throw new RuntimeException('Images Manager is undefined.');
        }
        return $this->_manager;
    }

}
