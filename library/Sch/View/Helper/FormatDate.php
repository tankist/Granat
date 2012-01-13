<?php
class Sch_View_Helper_FormatDate extends Zend_View_Helper_Abstract
{

    public function formatDate($date)
    {
        $month = (int)date("m", $date);
        $locale = new Zend_Locale();
        $months = $locale->getTranslationList('month');
        if (!empty($months)) {
            return date('d ', $date) . $months[$month];
        }
        return '';
    }

}
