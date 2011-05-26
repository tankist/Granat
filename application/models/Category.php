<?php

class Model_Category extends Skaya_Model_Abstract {

	protected $_modelName = 'Category';

    /**
     * @var Model_Mapper_Db_Category
     */
    protected $_mapper;

    public function getMapper() {
        if (!$this->_mapper) {
            $this->_mapper = new Model_Mapper_Decorator_Cache_Category(parent::getMapper());
        }
        return $this->_mapper;
    }

}