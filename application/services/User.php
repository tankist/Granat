<?php

use \Entities\User;

class Service_User extends Sch_Service_Abstract
{

    protected $_entityName = '\Entites\User';

    /**
     * @param string $firstName
     * @param string $email
     * @param string $password
     * @return Entities\User
     */
    public function create($firstName, $email, $password)
    {
        return new User($firstName, $email, $password);
    }

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
