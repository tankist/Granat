<?php
namespace Entities;

use \Doctrine\Common\Collections\ArrayCollection,
    \Doctrine\ORM\Mapping as ORM;

abstract class AbstractAttachment extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Путь к файлу вложения
     * @ORM\Column(type="string")
     */
    protected $filename;

    /**
     * Тип вложения
     * @ORM\Column(type="string",length=4)
     */
    protected $type;

    /**
     * @param string $filename
     * @return \Entities\AbstractAttachment
     */
    public function setFilename($filename)
    {
        $this->filename = pathinfo($filename, PATHINFO_BASENAME);
        if (empty($this->type)) {
            $this->type = pathinfo($this->filename, PATHINFO_EXTENSION);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $type
     * @return \Entities\AbstractAttachment
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @abstract
     * @return \Entities\Attachable
     */
    abstract public function getContainerEntity();

}
