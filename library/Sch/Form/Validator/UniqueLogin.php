<?php
class Sch_Form_Validator_UniqueLogin extends Zend_Validate_Abstract
{
    const NOT_UNIQUE = 'notUnique';

    /*
     * Сообщения об ошибках валидации
     */
    protected $_messageTemplates = array(
        self::NOT_UNIQUE => "Login '%value%' has already been taken"
    );

    public function isValid($value)
    {
        $this->_setValue($value);
        $isValid = true;

        $manager = new Service_User(Zend_Registry::get('em'));
        $user = $manager->getByEmail($value);
        if ($user) {
            $this->_error(self::NOT_UNIQUE);
            $isValid = false;
        }
        return $isValid;
    }
}
