<?php

class Model_DbTable_GrCollections extends Model_DbTable_Abstract
{

    protected $_name = 'gr_collections';

    protected $_primary = 'id';

    protected $_dependentTables = array('Model_DbTable_GrModels');


}

