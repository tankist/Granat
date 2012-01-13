<?php

class Sch_Controller_Action_Helper_IndexNavigation extends Sch_Controller_Action_Helper_AbstractNavigation
{
    public function getNavigation()
    {
        if (!$this->_navigation) {
            $this->_navigation = new Zend_Navigation(
                array()
            );
        }
        return $this->_navigation;
    }

    public function direct()
    {
        $navigation = parent::direct();
        Zend_Layout::getMvcInstance()->assign(array('mainNav' => $navigation));
        return $navigation;
    }

    public function preDispatch()
    {
        $this->direct();
    }

}
