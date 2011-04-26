<?php

class Model_DbTable_Photos extends Skaya_Model_DbTable_Abstract {

	protected $_name = 'gr_photos';

	protected $_primary = 'id';

	protected $_referenceMap = array('Models' => array(
		'columns' => array('model_id'),
		'refTableClass' => 'Model_DbTable_Models',
		'refColumns' => array('id')
	));

}