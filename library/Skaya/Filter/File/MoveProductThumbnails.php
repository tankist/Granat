<?php
class Skaya_Filter_File_MoveProductThumbnails implements Zend_Filter_Interface {

	protected $_from = '';

	protected $_to = '';

	protected $_thumbnailProperties;

	public function __construct($fromPath, $toPath) {
		$this->_thumbnailProperties = Model_Product::getThumbnailPack();
		//Add original image empty indication type
		array_push($this->_thumbnailProperties, array('indication_type' => ''));

		$this
			->setFrom($fromPath)
			->setTo($toPath);
	}

	public function filter($filename) {
		$filenameTokens = explode('.', $filename);
		$extension = array_pop($filenameTokens);
		$baseFilename = join('.', $filenameTokens);
		foreach ($this->_thumbnailProperties as $property) {
			$newBaseFilename = $baseFilename;
			switch ($property['indication_type']) {
				case 'prefix':
					$newBaseFilename = $property['indication'] . '_' . $newBaseFilename;
					break;
				case 'suffix':
					$newBaseFilename .= '_' . $property['indication'];
					break;
			}
			$newBaseFilename .= '.' . $extension;
			$thumbnailPath = realpath($this->getFrom() . DIRECTORY_SEPARATOR . $newBaseFilename);
			$newThumbnailPath = realpath($this->getTo()) . DIRECTORY_SEPARATOR . $newBaseFilename;
			if (file_exists($thumbnailPath)) {
				if (file_exists($newThumbnailPath)) {
					unlink($newThumbnailPath);
				}
				rename($thumbnailPath, $newThumbnailPath);
			}
		}
	}

	public function setFrom($from) {
		$this->_from = $from;
		return $this;
	}

	public function getFrom() {
		return $this->_from;
	}

	public function setTo($to) {
		$this->_to = $to;
		return $this;
	}

	public function getTo() {
		return $this->_to;
	}

}
