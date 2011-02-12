<?php

class Model_DbTable_Models extends Model_DbTable_Abstract
{

	protected $_name = 'gr_models';

	protected $_primary = 'id';

	protected $_dependentTables = array('Photos');

	protected $_referenceMap = array('
		Collections' => array(
			'columns' => array('collection_id'),
			'refTableClass' => 'Collections',
			'refColumns' => array('id')
			));


}

