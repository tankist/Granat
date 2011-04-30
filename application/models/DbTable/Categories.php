<?php

class Model_DbTable_Categories extends Skaya_Model_DbTable_Abstract {

	protected $_name = 'gr_categories';

	protected $_primary = 'id';

	protected $_dependentTables = array('Model_DbTable_ModelCategories');

}