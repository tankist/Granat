<?php
class Skaya_View_Helper_OrderLink extends Zend_View_Helper_HtmlElement {

	const ORDER_TYPE_ASC = 'asc';

	const ORDER_TYPE_DESC = 'desc';

	public function orderLink($text, $orderField = null, $urlParams = array()) {
		$textAppend = '';
		if (!$orderField) {
			$orderField = preg_replace('$[^\w\d]+$i', '', $text);
		}
		if (!is_array($urlParams)) {
			$urlParams  =(array)$urlParams;
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
		return '<a href="' . $this->view->url($urlParams) . '">' . $text . '</a>' . $textAppend;
	}

}
