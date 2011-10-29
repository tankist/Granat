<?php

namespace Entities;

/**
 * @Entity
 * @Table(name="model_photos")
 */
class ModelPhoto extends AbstractPhoto
{

    /**
     * @var \Entities\Model
     * @ManyToOne(targetEntity="Model", inversedBy="photos")
     */
    protected $model;

    /**
     * @param \Entities\Model $model
     * @return \Entities\ModelPhoto
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
