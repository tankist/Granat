<?php

namespace Entities;

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="models")
 */
class Model extends AbstractEntity
{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(type="text")
     */
    protected $title;

    /**
     * @var string
     * @Column(type="text", nullable="true")
     */
    protected $description;

    /**
     * @var \Entities\Collection
     * @ManyToOne(targetEntity="Collection", inversedBy="models")
     */
    protected $collection;

    /**
     * @var \Entities\Category
     * @ManyToOne(targetEntity="Category", inversedBy="models")
     */
    protected $category;

    /**
     * @var \Entities\Model\Photo
     * @OneToOne(targetEntity="Photo")
     */
    protected $mainPhoto;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="Photo", mappedBy="model", cascade={"persist", "remove"})
     */
    protected $photos;

    public function __construct($title)
    {
        $this->title = $title;
        $this->photos = new ArrayCollection();
    }

    /**
     * @param \Entities\Category $category
     * @return \Entities\Model
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return \Entities\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \Entities\Collection $collection
     * @return \Entities\Model
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return \Entities\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param string $description
     * @return \Entities\Model
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
     * @param \Entities\Model\Photo $mainPhoto
     * @return \Entities\Model
     */
    public function setMainPhoto($mainPhoto)
    {
        if ($mainPhoto->getModel()->getId() != $this->getId()) {
            throw new \InvalidArgumentException('Photo has to be in this model to set it as main');
        }
        $this->mainPhoto = $mainPhoto;
        return $this;
    }

    /**
     * @return \Entities\Model\Photo
     */
    public function getMainPhoto()
    {
        return $this->mainPhoto;
    }

    /**
     * @param string $title
     * @return \Entities\Model
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
     * @param \Entities\Model\Photo $photo
     * @return \Entities\Model
     */
    public function addPhoto(\Entities\Model\Photo $photo)
    {
        $this->photos[] = $photo;
        $photo->setModel($this);
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}
