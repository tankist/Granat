<?php

class Sch_View_Helper_Stars extends Zend_View_Helper_Abstract
{

    const STARS_TYPE_SHOP = 'shop';

    const STARS_TYPE_PRODUCTS = 'products';

    const STARS_TYPE_SERVICE = 'service';

    protected $_marks = array(
        self::STARS_TYPE_SHOP => array(
            'Не оцененно',
            'Ужасный магазин (-5 баллов)',
            'Плохой магазин (-2 балла)',
            'Обычный магазин (0 баллов)',
            'Хороший магазин (+2 балла)',
            'Отличный магазин (+5 баллов)'
        ),
        self::STARS_TYPE_PRODUCTS => array(
            'Неизвестно',
            'Плохо',
            'Неудовлетворительно',
            'Нормально',
            'Хорошо',
            'Очень хорошо'
        ),
        self::STARS_TYPE_SERVICE => array(
            'Неизвестно',
            'Не очень качественное обслуживание',
            'Не очень качественное обслуживание',
            'Хороший персонал',
            'Хороший персонал',
            'Отличное обслуживание'
        )
    );

    protected $_points = array(0, -5, -2, 0, 2, 5);

    public function stars()
    {
        return $this;
    }

    public function display($num)
    {
        $result = '';
        for ($i = 1; $i < count($this->_points); $i++) {
            if ($i <= $num) {
                $result .= '<i class="yell"></i>';
            } else {
                $result .= '<i></i>';
            }
        }
        return $result;
    }

    public function mark($num, $type = self::STARS_TYPE_PRODUCTS)
    {
        if (!array_key_exists($type, $this->_marks)) {
            throw new Zend_View_Exception('Type not found');
        }
        $marks = $this->_marks[$type];
        if (!$num || !($num > 0 && $num < count($marks))) {
            $num = 0;
        }
        return $marks[$num];
    }

}
