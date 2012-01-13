<?php

require_once 'Zend/Application.php';

class Skaya_Application extends Zend_Application
{

    /**
     * @var Zend_Cache_Core
     */
    protected $_configCache;

    /**
     * @param string $environment
     * @param array|Zend_Config $options
     * @param Zend_Cache_Core $cache
     */
    public function __construct($environment, $options = null, Zend_Cache_Core $cache)
    {
        $this->setConfigCache($cache);
        parent::__construct($environment, $options);
    }

    /**
     * @param Zend_Cache_Core $configCache
     * @return Skaya_Application
     */
    public function setConfigCache(Zend_Cache_Core $configCache)
    {
        $this->_configCache = $configCache;
        return $this;
    }

    /**
     * @return Zend_Cache_Core
     */
    public function getConfigCache()
    {
        return $this->_configCache;
    }

    /**
     * Get cache ID for save/retrieve cached config
     * @param  $file
     * @return string
     */
    protected function _cacheId($file)
    {
        return md5($file . '_' . $this->getEnvironment());
    }

    protected function _loadConfig($file)
    {
        $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!($cache = $this->getConfigCache()) || in_array($suffix, array('php', 'inc'))) {
            return parent::_loadConfig($file);
        }
        $cache_id = $this->_cacheId($file);
        $configLastModified = filemtime($file);
        $cacheLastModified = $cache->test($cache_id);
        if ($cacheLastModified !== false && $cacheLastModified > $configLastModified) {
            return $cache->load($cache_id);
        }
        $config = parent::_loadConfig($file);
        $cache->save($config, $cache_id, array(), null);
        return $config;
    }

}
