<?php
class Sch_Form_Validator_JoinLogin extends Zend_Validate_Abstract
{
    const NOT_JOIN = 'notJoin';

    /*
     * Сообщения об ошибках валидации
     */
    protected $_messageTemplates = array(
        self::NOT_JOIN => "Пользователь '%value%' не найден."
    );

    public function isValid($value)
    {
        $this->_setValue($value);
        $isValid = true;

        $manager = new Service_User(Zend_Registry::get('em'));
        $user = $manager->getByEmail($value);
        if (!$user) {
            $this->_error(self::NOT_JOIN);
            $isValid = false;
        }
        return $isValid;
    }
}
