<?php

class Application_Service_User extends Skaya_Model_Service_Abstract
{

    public function create($data = array ())
    {
        return new Application_Model_User($data);
    }

    public function getUserById($id)
    {
        $userData = $this->_mappers->user->getUserById($id);
        return self::create($userData);
    }

    public function getUsers($order = null, $count = null, $offset = null)
    {
        $usersBlob = $this->_mappers->user->getUsers($order, $count, $offset);
        return new Model_Collection_Users($usersBlob);
    }

    public function getUsersPaginator($order = null)
    {
        $paginator = $this->_mappers->user->getUsersPaginator($order);
        $paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Users'));
        return $paginator;
    }


}

