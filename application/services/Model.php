<?php

use \Entities\Model, \Entities\ModelPhoto;

class Service_Model extends Sch_Service_Abstract
{

    protected $_entityName = '\Entites\Model';

    /**
     * @param string $title
     * @return Entities\Model
     */
    public function create($title)
    {
        return new Model($title);
    }

    /**
     * @param string $filename
     * @return Entities\ModelPhoto
     */
    public function createPhoto($filename)
    {
        return new ModelPhoto($filename);
    }

    /**
     * @param int $id
     * @return \Entities\ModelPhoto
     */
    public function getPhotoById($id)
    {
        $model = $this->getEntityManager()->getRepository('\Entities\ModelPhoto')->findOneBy(array('id' => (int)$id));
        return $model;
    }

    public function getPhotos()
    {
        return $this->getEntityManager()->getRepository('\Entities\ModelPhoto')->findAll();
    }

    public function getModelsPaginator($order = null)
    {
        // @todo
    }

    public function getRandomModels($count = null)
    {
        // @todo
    }

}
