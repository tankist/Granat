<?php
class Skaya_Controller_Action_Helper_ProductImages extends Zend_Controller_Action_Helper_Abstract {

	public function direct(Model_Product $product) {
		$imagesPathHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('ImagePath');
		$images = $product->getImages();
		$imagesPaths = array();
		foreach ($images as /** @var Model_ProductImage */$image) {
			$imagesPaths[$image->id] = array(
				'path' => $imagesPathHelper->direct($product),
				'name' => $image->name
			);
		}
		return $imagesPaths;
	}

}
