<?php
namespace Entities;

use \Doctrine\Common\Collections\ArrayCollection,
    \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Repository\Collection")
 * @ORM\Table(name="collections")
 */
class Collection extends AbstractEntity
{

    /**
     * @var int
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Model", mappedBy="collection", cascade={"persist", "remove"})
     */
    protected $models;

    /**
     * @var \Entities\Model
     * @ORM\OneToOne(targetEntity="Model")
     * @ORM\JoinColumn(name="main_model_id", referencedColumnName="id")
     */
    protected $mainModel;

    /**
     * @param $title
     */
    public function __construct($title)
    {
        $this->title = $title;
        $this->models = new ArrayCollection();
    }

    /**
     * @param string $description
     * @return \Entities\Collection
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Entities\Model $mainModel
     * @return \Entities\Collection
     */
    public function setMainModel(Model $mainModel)
    {
        if ($mainModel->getCollection()->getId() != $this->getId()) {
            throw new \InvalidArgumentException('Model has to be in this collection to set it as main');
        }
        $this->mainModel = $mainModel;
        return $this;
    }

    /**
     * @return \Entities\Model
     */
    public function getMainModel()
    {
        return $this->mainModel;
    }

    /**
     * @param string $title
     * @return \Entities\Collection
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param \Entities\Model $model
     * @return \Entities\Collection
     */
    public function addModel(Model $model)
    {
        $this->models[] = $model;
        $model->setCollection($this);
        return $this;
    }

    /**
     * @return \Entities\Model[]
     */
    public function getModels()
    {
        return $this->models;
    }

}
