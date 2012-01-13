<?php

use \Entities\User;

class Sch_View_Helper_User extends Zend_View_Helper_Abstract
{

    public function user(User $user, $isCurrentUser = false)
    {
        $login = $user->getLogin();
        $url = $this->view->url(array('login' => $login), 'userProfile');
        $compose = $this->view->url(array('login' => $login), 'userMailNew');
        $favor = $this->view->url(array('login' => $login), 'userFavor');
        $tpl = <<<ELF
<div class="nickname name"><a class="no-link" href="%2\$s">%s</a>
    <div class="user-links"><a class="arr" href="%s"><img src="/i/round-arr.png" alt="%1\$s"></a>
        <div class="links-wrap" style="display: none; ">
            <ul>
                <li><a class="favor" href="%s">В избранное</a></li>
                <li><a class="mail" href="%s">Написать сообщение</a></li>
            </ul>
            <i class="ls"></i><i class="rs"></i>
        </div>
    </div>
</div>
ELF;
        if ($isCurrentUser) {
            $tpl = '<div class="nickname name">%s</div>';
        }
        return sprintf($tpl, $login, $url, $favor, $compose);
    }

}
