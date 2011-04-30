<?php

class Model_DbTable_Models extends Skaya_Model_DbTable_Abstract {

	protected $_name = 'gr_models';

	protected $_primary = 'id';

	protected $_dependentTables = array('Model_DbTable_Photos', 'Model_DbTable_ModelCategories');

	protected $_referenceMap = array('Collections' => array(
		'columns' => array('collection_id'),
		'refTableClass' => 'Model_DbTable_Collections',
		'refColumns' => array('id')
	));

}