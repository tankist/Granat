<?php

class Sch_View_Helper_Date extends Zend_View_Helper_Abstract
{

    public function date($date, $format = Zend_Date::DATE_MEDIUM)
    {
        if ($date instanceof DateTime) {
            $date = $date->getTimestamp();
        }
        $date = new Zend_Date($date);
        return $date->toString($format);
    }

}
