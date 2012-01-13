<?php

class Sch_View_Helper_UserStatus extends Zend_View_Helper_Abstract
{

    public function userStatus(\Entities\User $user)
    {
        if ($user->isOnline()) {
            $class = 'on';
            $text = 'сейчас на сайте';
        }
        else {
            $class = 'off';
            $text = 'нет на сайте';
        }
        return sprintf('<div class="status"><span class="%s">%s</span></div>', $class, $text);
    }

}
