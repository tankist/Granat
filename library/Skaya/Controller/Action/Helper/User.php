<?php
class Skaya_Controller_Action_Helper_User extends Zend_Controller_Action_Helper_Abstract
{

    protected $_user;

    public function direct()
    {
        if (!$this->_user) {
            $user = $this->getRequest()->getParam('__user');
            $user_id = null;
            if ($user instanceof \Entities\User) {
                $user_id = $user->id;
            }
            elseif (is_array($user)) {
                $user_id = $user['id'];
            }
            $this->_user = $this->getActionController()->getHelper('service')->direct('User')->getUserById($user_id);
        }
        return $this->_user;
    }

}
