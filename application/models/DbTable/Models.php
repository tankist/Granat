<?php

class Model_DbTable_Models extends Skaya_Model_DbTable_Abstract {

	protected $_name = 'gr_models';

	protected $_primary = 'id';

	protected $_dependentTables = array('Model_DbTable_Photos');

	protected $_referenceMap = array(
		'Collection' => array(
			'columns' => array('collection_id'),
			'refTableClass' => 'Model_DbTable_Collections',
			'refColumns' => array('id')
		),
		'Category' => array(
			'columns' => array('category_id'),
			'refTableClass' => 'Model_DbTable_Categories',
			'refColumns' => array('id')
		)
	);

}