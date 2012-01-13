<?php

class Sch_Filter_Video_Youtube extends Sch_Filter_Video_Abstract
{

    protected $_host = 'youtube.com';

    protected $_width;

    protected $_height;

    protected $_hideRelatedVideos = false;

    protected $_fullscreenAllowed = true;

    protected function _getEmbedCode(Zend_Uri $url)
    {
        /**
         * @var Zend_Uri_Http $url
         */
        $attribs = array('frameborder="0"');
        $url->setHost('www.youtube.com');
        $query = $url->getQueryAsArray();
        if (!isset($query['v'])) {
            return false;
        }
        $url->setPath(sprintf('/embed/%s', $query['v']));
        $query = array();

        if (($width = $this->getWidth()) && $width > 0) {
            $attribs[] = sprintf('width="%d"', $width);
        }
        if (($height = $this->getHeight()) && $height > 0) {
            $attribs[] = sprintf('height="%d"', $height);
        }
        if ($this->getHideRelatedVideos()) {
            $query['rel'] = 0;
        }
        if ($this->getFullscreenAllowed()) {
            $attribs[] = 'allowfullscreen';
        }

        $url->setQuery($query);

        $attribs[] = sprintf('src="%s"', $url->getUri());
        return sprintf('<iframe %s></iframe>', join(' ', $attribs));
    }

    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setHideRelatedVideos($hideRelatedVideos)
    {
        $this->_hideRelatedVideos = $hideRelatedVideos;
        return $this;
    }

    public function getHideRelatedVideos()
    {
        return $this->_hideRelatedVideos;
    }

    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function setFullscreenAllowed($fullscreenAllowed)
    {
        $this->_fullscreenAllowed = $fullscreenAllowed;
        return $this;
    }

    public function getFullscreenAllowed()
    {
        return $this->_fullscreenAllowed;
    }

}
