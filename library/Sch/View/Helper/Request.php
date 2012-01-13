<?php
class Sch_View_Helper_Request extends Zend_View_Helper_Abstract
{

    public function request($section = null)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if (in_array($section, array('module', 'controller', 'action'))) {
            $getter = 'get' . ucfirst($section) . 'Name';
            if (method_exists($request, $getter)) {
                return call_user_func(array($request, $getter));
            }
        }
        return $request;
    }

}
