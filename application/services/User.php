<?php

use \Entities\User;

/**
 * @class Service_User
 */
class Service_User extends Sch_Service_Abstract
{

    protected $_entityName = '\Entities\User';

    /**
     * @param string $email
     * @return \Entities\User
     */
    public function getByEmail($email)
    {
        $user = $this->getRepository()->findOneBy(array('email' => $email));
        return $user;
    }

}
