<?php

class Sch_View_Helper_Announce extends Zend_View_Helper_Abstract
{

    public function announce($text, $length = 100, $etc = '…')
    {
        if ($length > 0) {
            $len = mb_strlen($text, 'UTF8');
            if ($len > $length + 1) {
                return mb_substr($text, 0, $length, 'UTF8') . $etc;
            }
        }
        return $text;
    }

}
