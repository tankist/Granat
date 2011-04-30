<?php

class Model_DbTable_ModelCategories extends Skaya_Model_DbTable_Abstract {

	protected $_name = 'gr_model_categories';

	protected $_primary = array('category_id', 'model_id');

	protected $_referenceMap = array(
		'Category' => array(
			'columns' => array('category_id'),
			'refTableClass' => 'Model_DbTable_Categories',
			'refColumns' => array('id')
		),
		'Model' => array(
			'columns' => array('model_id'),
			'refTableClass' => 'Model_DbTable_Models',
			'refColumns' => array('id')
		),
	);

}