<?php

class Sch_View_Helper_Twitter_Navigation_Breadcrumbs extends Zend_View_Helper_Navigation_Breadcrumbs
{
    public function __construct()
    {
        $this->setSeparator('<span class="divider">' . $this->getSeparator() . '</span>');
    }

    public function renderStraight(Zend_Navigation_Container $container = null)
    {
        $breadcrumbs = parent::renderStraight($container);
        return (!empty($breadcrumbs)) ? '<ul class="breadcrumb">' . $breadcrumbs . '</ul>' : '';
    }

    public function htmlify(Zend_Navigation_Page $page)
    {
        $element = parent::htmlify($page);
        return (!empty($element)) ? '<li>' . $element . '</li>' : '';
    }

}
