<?php
class Sch_View_Helper_Ending extends Zend_View_Helper_Abstract
{
    /**
     * Возвращает правильный вариант окончания существительного для числа.
     *
     * @param integer $num число
     * @param string  $v1 первый вариант (1)
     * @param string  $v2 второй вариант (2-5)
     * @param string  $v3 третий вариант (0, 5-20)
     * @return string вариант, соответствующий числу.
     */
    public function ending($num, $v1, $v2, $v3)
    {
        $num = intval($num);
        $e = $num % 10;

        if ((($num == 0) || (($num > 5) && ($num < 20))) || (($e == 0) || ($e > 4))) {
            $result = $v3;
        }
        elseif ($e == 1) {
            $result = $v1;
        }
        else {
            $result = $v2;
        }
        return $result;
    }
}
