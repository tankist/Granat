<?php

namespace Model\DbTable;

class Collections extends AbstractDbTable
{

	protected $_name = 'gr_collections';

	protected $_primary = 'id';

	protected $_dependentTables = array('Models');


}

