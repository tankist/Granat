<?php

class Model_DbTable_Photos extends Model_DbTable_Abstract
{

	protected $_name = 'gr_photos';

	protected $_primary = 'id';

	protected $_referenceMap = array('Models' => array(
			'columns' => array('model_id'),
			'refTableClass' => 'Models',
			'refColumns' => array('id')
			));


}

