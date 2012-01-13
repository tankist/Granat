<?php

class Sch_Filter_Video_Vimeo extends Sch_Filter_Video_Abstract
{

    protected $_host = 'vimeo.com';

    protected $_width;

    protected $_height;

    protected $_autoplay = false;

    protected $_loop = false;

    protected function _getEmbedCode(Zend_Uri $url)
    {
        /**
         * @var Zend_Uri_Http $url
         */
        $attribs = array('frameborder="0"');
        $url->setHost('player.vimeo.com');
        $url->setPath('/video' . $url->getPath());
        $query = $url->getQueryAsArray();

        if (($width = $this->getWidth()) && $width > 0) {
            $attribs[] = sprintf('width="%d"', $width);
        }
        if (($height = $this->getHeight()) && $height > 0) {
            $attribs[] = sprintf('height="%d"', $height);
        }
        if ($this->getAutoplay()) {
            $query['autoplay'] = 1;
        }
        if ($this->getLoop()) {
            $query['loop'] = 1;
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

    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function setAutoplay($autoplay)
    {
        $this->_autoplay = $autoplay;
        return $this;
    }

    public function getAutoplay()
    {
        return $this->_autoplay;
    }

    public function setLoop($loop)
    {
        $this->_loop = $loop;
        return $this;
    }

    public function getLoop()
    {
        return $this->_loop;
    }

}
