<?php
class Skaya_View_Helper_OrderLink extends Zend_View_Helper_HtmlElement
{

    const ORDER_TYPE_ASC = 'asc';

    const ORDER_TYPE_DESC = 'desc';

    public function orderLink($text, $orderField = null, $urlParams = array())
    {
        $textAppend = '';
        if (!$orderField) {
            $orderField = preg_replace('$[^\w\d]+$i', '', $text);
        }
        if (!is_array($urlParams)) {
            $urlParams = (array)$urlParams;
        }
        $currentField = $this->view->order;
        $currentOrderType = $this->view->orderType;
        $urlParams['order'] = $orderField;
        $urlParams['orderType'] = self::ORDER_TYPE_ASC;
        if ($currentField && $currentField == $orderField) {
            $textAppend = '<span class="desc">▼<span>';
            if ($currentOrderType && $currentOrderType == self::ORDER_TYPE_ASC) {
                $urlParams['orderType'] = self::ORDER_TYPE_DESC;
                $textAppend = '<span class="asc">▲<span>';
            }
        }
        return '<a href="' .
            $this->view->url($urlParams) .
            $this->_serializeQueryString($_GET) . '">' .
            $text . '</a>' . $textAppend;
    }

    protected function _serializeQueryString($params, $name = null)
    {
        $ret = "";
        foreach ($params as $key => $val) {
            if (is_array($val)) {
                if ($name == null) {
                    $ret .= $this->_serializeQueryString($val, $key);
                }
                else {
                    $ret .= $this->_serializeQueryString($val, $name . "[$key]");
                }
            } else {
                if ($name != null) {
                    $ret .= $name . "[$key]" . "=$val&";
                }
                else {
                    $ret .= "$key=$val&";
                }
            }
        }
        if (!empty($ret)) {
            $ret = '?' . $ret;
        }
        return $ret;
    }

}
