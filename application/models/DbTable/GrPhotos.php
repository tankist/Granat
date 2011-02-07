<?php

class Model_DbTable_GrPhotos extends Model_DbTable_Abstract
{

    protected $_name = 'gr_photos';

    protected $_primary = 'id';

    protected $_referenceMap = array('GrModels' => array(
            'columns' => array('model_id'),
            'refTableClass' => 'Model_DbTable_GrModels',
            'refColumns' => array('id')
            ));


}

