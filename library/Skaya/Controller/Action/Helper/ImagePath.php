<?php
class Skaya_Controller_Action_Helper_ImagePath extends Zend_Controller_Action_Helper_Abstract {

	public function direct(Model_Product $product = null) {
		$root = realpath($this->getRequest()->getServer('DOCUMENT_ROOT')) . DIRECTORY_SEPARATOR;
		if ( $product && !$product->isEmpty() ) {
			$path = join('/', array('uploads', 'products', $product->id));
		} else {
			$path = join('/', array('uploads', 'temp', 'products'));
		}
		if (!is_dir($root . $path)) {
			mkdir($root . $path,0777,true);
		}
		return $path;
	}

}
