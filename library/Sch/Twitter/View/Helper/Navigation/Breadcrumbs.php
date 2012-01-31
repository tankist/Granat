<?php

/**
 * @class Sch_Twitter_View_Helper_Navigation_Breadcrumbs
 */
class Sch_Twitter_View_Helper_Navigation_Breadcrumbs extends Zend_View_Helper_Navigation_Breadcrumbs
{
    /**
     * @constructor
     */
    public function __construct()
    {
        $this->setSeparator('<span class="divider">' . $this->getSeparator() . '</span>');
    }

    /**
     * @param null|Zend_Navigation_Container $container
     * @return string
     */
    public function renderStraight(Zend_Navigation_Container $container = null)
    {
        $breadcrumbs = parent::renderStraight($container);
        return (!empty($breadcrumbs)) ? '<ul class="breadcrumb">' . $breadcrumbs . '</ul>' : '';
    }

    /**
     * @param Zend_Navigation_Page $page
     * @return string
     */
    public function htmlify(Zend_Navigation_Page $page)
    {
        $element = parent::htmlify($page);
        return (!empty($element)) ? '<li>' . $element . '</li>' : '';
    }

}
