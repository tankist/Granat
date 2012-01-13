<?php
class Skaya_Controller_Plugin_MultilingualRouter extends Zend_Controller_Plugin_Abstract
{

    /**
     * @var string
     */
    protected $_defaultLanguage;

    public function __construct($defaultLanguage)
    {
        $this->setDefaultLanguage($defaultLanguage);
    }

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $uri = $request->getRequestUri();
        if (!is_int(stripos($uri, '/ru')) && !is_int(stripos($uri, '/en'))) {
            $uri = '/' . $this->getDefaultLanguage() . $uri;
            $request->setRequestUri($uri)->setPathInfo();
            $request->setParam('lang', $this->getDefaultLanguage());
        }
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $lang = $request->getParam('lang');
        $translate = $this->_getTranslate();
        $translate->getAdapter()->setLocale($lang);
        /**
         * @var Zend_Controller_Router_Rewrite $router
         */
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->setGlobalParam('lang', $lang);
    }

    /**
     * @return Zend_Translate
     */
    protected function _getTranslate()
    {
        return Zend_Registry::get('Zend_Translate');
    }

    /**
     * @param string $defaultLanguage
     * @return Skaya_Controller_Plugin_MultilingualRouter
     */
    public function setDefaultLanguage($defaultLanguage)
    {
        $this->_defaultLanguage = $defaultLanguage;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultLanguage()
    {
        return $this->_defaultLanguage;
    }

}
