<?php

class Sch_View_Helper_Cut extends Zend_View_Helper_Abstract
{

    /**
     * @var Sch_Filter_Cut
     */
    protected $_cutFilter;

    public function cut($text, $lines = 5)
    {
        $this->_getCutFilter()->setLines($lines);
        return $this->_getCutFilter()->filter($text);
    }

    /**
     * @return Sch_Filter_Cut
     */
    protected function _getCutFilter()
    {
        if (!$this->_cutFilter) {
            $this->_cutFilter = new Sch_Filter_Cut();
        }
        return $this->_cutFilter;
    }

}
