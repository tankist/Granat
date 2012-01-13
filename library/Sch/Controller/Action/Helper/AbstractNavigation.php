<?php

abstract class Sch_Controller_Action_Helper_AbstractNavigation extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var Zend_Navigation_Container
     */
    protected $_navigation;

    /**
     * @abstract
     * @return Zend_Navigation_Container
     */
    abstract public function getNavigation();

    public function direct()
    {
        $navigation = $this->getNavigation();
        if ($navigation &&
            $activePage = $this->_findNewActivePage($navigation)
        ) {
            foreach ($navigation->getPages() as /** @var Zend_Navigation_Page_Mvc $page */
                     $page) {
                if ($page->isActive()) {
                    $page->setActive(false);
                    break;
                }
            }
            $activePage->setActive(true);
        }
        return $navigation;
    }

    protected function _findNewActivePage(Zend_Navigation_Container $navigation)
    {
        $request = $this->getRequest();
        /** @var $router Zend_Controller_Router_Rewrite */
        $router = $this->getFrontController()->getRouter();

        $ancestors = array(
            'action' => $request->getActionName(),
            'controller' => $request->getControllerName(),
            'module' => $request->getModuleName(),
            'route' => $router->getCurrentRouteName()
        );

        $iterator = new RecursiveIteratorIterator($navigation,
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as /** @var Zend_Navigation_Page_Mvc $page */
                 $page) {
            $found = true;
            $hasAncestor = false;
            foreach ($ancestors as $key => $value) {
                if ($value && ($compareValue = $page->get($key))) {
                    $hasAncestor = true;
                    $found = $found && ($value == $compareValue);
                }
            }
            if ($hasAncestor && $found) {
                return $page;
            }
        }

        return false;
    }

}
