<?php

use \Entities\Collection;

class Service_Collection extends Sch_Service_Abstract
{

    protected $_entityName = '\Entites\Collection';

    /**
     * @param string $title
     * @return Entities\Collection
     */
    public function create($title)
    {
        return new Collection($title);
    }

    public function getCollectionsPaginator($order = null)
    {
        // @todo
    }

    public function getNonEmptyCollectionsPaginator($order = null)
    {
        // @todo
    }

}
