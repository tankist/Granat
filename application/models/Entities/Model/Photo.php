<?php

namespace Entities\Model;

/**
 * @Entity
 * @Table(name="model_photos")
 */
class Photo extends \Entities\AbstractPhoto
{

    /**
     * @var \Entities\Model
     * @ManyToOne(targetEntity="Model", inversedBy="photos")
     */
    protected $model;

    /**
     * @param \Entities\Model $model
     * @return \Entities\Model\Photo
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return \Entities\Model
     */
    public function getModel()
    {
        return $this->model;
    }
}
