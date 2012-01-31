<?php
namespace Entities;

use \Doctrine\Common\Collections\ArrayCollection,
    \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category extends AbstractEntity
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
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Model", mappedBy="category", cascade={"persist", "remove"})
     */
    protected $models;

    /**
     * @param $title
     */
    public function __construct($title)
    {
        $this->title = $title;
        $this->models = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return \Entities\Category
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
     * @return \Entities\Category
     */
    public function addModel(Model $model)
    {
        $this->models[] = $model;
        $model->setCategory($this);
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getModels()
    {
        return $this->models;
    }
}
