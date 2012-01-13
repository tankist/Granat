<?php
class Skaya_Controller_Action_Helper_Translator extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var Zend_Translate
     */
    protected $_translate;

    public function preDispatch()
    {
        $request = $this->getRequest();
        $this->getActionController()->view->language = $language = $request->getParam('lang');
        $locales = array('ru' => 'ru_RU', 'en' => 'en_US');
        if (Zend_Locale::isLocale($language)) {
            if (array_key_exists($language, $locales)) {
                $language = $locales[$language];
            }
            Zend_Locale::setDefault($language);
            $this->_translate = Zend_Registry::get('Zend_Translate');
            $this->_translate->getAdapter()->setLocale($language);
        }
    }

    public function direct()
    {
        return $this->_translate;
    }

}
