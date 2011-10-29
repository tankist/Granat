<?php

use \Entities\Category;

class Service_Category extends Sch_Service_Abstract
{

    protected $_entityName = '\Entites\Category';

    /**
     * @param string $title
     * @return Entities\Category
     */
    public function create($title)
    {
        return new Category($title);
    }

    public function getCategoriesPaginator($order = null)
    {
        // @todo
    }

}
