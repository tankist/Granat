<?php

class Model_DbTable_GrModels extends Model_DbTable_Abstract
{

    protected $_name = 'gr_models';

    protected $_primary = 'id';

    protected $_dependentTables = array('Model_DbTable_GrPhotos');

    protected $_referenceMap = array('GrCollections' => array(
            'columns' => array('collection_id'),
            'refTableClass' => 'Model_DbTable_GrCollections',
            'refColumns' => array('id')
            ));


}

