<?php

class Model_Mapper_Decorator_Cache_Fabric extends Skaya_Model_Mapper_Decorator_Cache {

    public function getCacheId($method, $params = array()) {
        switch ($method) {
            case 'save':
            case 'delete':
                $data = array_shift($params);
                if (is_array($data) && array_key_exists('id', $data)) {
                    return 'fabric_' . $data['id'];
                }
                break;
        }
        return parent::getCacheId($method, $params);
    }

    public function getCacheTags($method, $params = array()) {
        switch ($method) {
            case 'save':
            case 'delete':
                return array('list', 'fabrics');
                break;
        }
        return parent::getCacheTags($method, $params);
    }

}
