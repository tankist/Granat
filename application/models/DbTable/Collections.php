<?php

class Model_DbTable_Collections extends Skaya_Model_DbTable_Abstract {

	protected $_name = 'gr_collections';

	protected $_primary = 'id';

	protected $_dependentTables = array('Model_DbTable_Models');

}