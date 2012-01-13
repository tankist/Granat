<?php

class Sch_Filter_Video_Rutube extends Sch_Filter_Video_Abstract
{

    protected $_host = 'rutube.ru';

    protected $_width;

    protected $_height;

    protected $_fullscreenAllowed = true;

    protected function _getEmbedCode(Zend_Uri $url)
    {
        /**
         * @var Zend_Uri_Http $url
         */
        $query = $url->getQueryAsArray();
        if (!isset($query['v'])) {
            return false;
        }
        $url->setPath('/' . $query['v']);
        $url->setHost('video.rutube.ru');

        $attribs = $params = array();
        if (($width = $this->getWidth()) && $width > 0) {
            $attribs['width'] = $params['width'] = (string)$width;
        }
        if (($height = $this->getHeight()) && $height > 0) {
            $attribs['height'] = $params['height'] = (string)$height;
        }
        if ($this->getFullscreenAllowed()) {
            $params['allowFullScreen'] = 'true';
        }
        $params['wmode'] = 'window';

        $helper = new Zend_View_Helper_HtmlFlash();
        $view = new Zend_View();
        $view->doctype(Zend_View_Helper_Doctype::HTML5);
        $helper->setView($view);
        return $helper->htmlFlash($url->getUri(), $attribs, $params);
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
