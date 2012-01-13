<?php

abstract class Sch_Filter_Video_Abstract implements Zend_Filter_Interface
{

    protected $_host = '';

    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        foreach ((array)$options as $option => $value) {
            $setterName = 'set' . ucfirst($option);
            if (method_exists($this, $setterName)) {
                call_user_func(array($this, $setterName), $value);
            }
        }
    }

    public function filter($value)
    {
        if (preg_match_all('$\<video\>([^\<\>]+)\<\/video\>$i', $value, $urls)) {
            $urls = $urls[1];
            foreach ((array)$urls as $url) {
                $placeholder = sprintf('<video>%s</video>', $url);
                try {
                    /**
                     * @var Zend_Uri_Http $url
                     */
                    $url = Zend_Uri::factory($url);
                    if (strpos($url->getHost(), $this->_host) !== false) {
                        $embed = $this->_getEmbedCode($url);
                        $value = str_replace($placeholder, $embed, $value);
                    }
                }
                catch (Zend_Uri_Exception $e) {
                    continue;
                }
            }
        }
        return $value;
    }

    abstract protected function _getEmbedCode(Zend_Uri $url);

}
