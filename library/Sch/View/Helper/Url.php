<?php

class Sch_View_Helper_Url extends Zend_View_Helper_Url
{
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        if (
            is_array($urlOptions) &&
            !empty($urlOptions) &&
            !array_key_exists('module', $urlOptions) &&
            !array_key_exists('action', $urlOptions) &&
            !array_key_exists('controller', $urlOptions)
        ) {
            /** @var $router Zend_Controller_Router_Rewrite */
            $router = Zend_Controller_Front::getInstance()->getRouter();
            if ($name === null) {
                $name = $router->getCurrentRouteName();
            }
            if (
                array_key_exists('type', $urlOptions) &&
                in_array($urlOptions['type'], array('users', 'shops'))
            ) {
                $name = $urlOptions['type'] . 'Blogs';
            }
            if (isset($urlOptions['page']) && intval($urlOptions['page']) > 1 && $router->hasRoute('p_' . $name)) {
                $name = 'p_' . $name;
            }
            if (isset($urlOptions['tag']) && $router->hasRoute($name . '_tags')) {
                $name .= '_tags';
            }
            if (isset($urlOptions['sort']) && $router->hasRoute($name . '_sort')) {
                $name .= '_sort';
            }
            if (isset($urlOptions['theme']) && $router->hasRoute($name . '_theme')) {
                $name .= '_theme';
            }
        }
        return parent::url($urlOptions, $name, $reset, $encode);
    }
}
