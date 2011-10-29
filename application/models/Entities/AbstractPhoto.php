<?php

namespace Entities;

abstract class AbstractPhoto extends AbstractEntity
{

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Путь к файлу вложения
     * @Column(type="string")
     */
    protected $filename;

    /**
     * Тип вложения
     * @Column(type="string",length=4)
     */
    protected $type;

    public function __construct($filename)
    {
        $this->setFilename($filename);
    }

    /**
     * @param string $filename
     * @return \Entities\AbstractAttachment
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
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

}
