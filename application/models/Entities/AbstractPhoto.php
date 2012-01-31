<?php

namespace Entities;

abstract class AbstractPhoto extends AbstractAttachment
{

    public static $thumbSettings = array();

    /**
     * @param null $size
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getThumbnailFilename($size = null)
    {
        if (!$size) {
            return $this->getFilename();
        }
        if (!($setting = $this->getThumbSetting($size))) {
            throw new \InvalidArgumentException('Wrong size provided');
        }
        $filter = new \Sch_Filter_ThumbFilename();
        $filter
                ->setPrefix((isset($setting['prefix']))?$setting['prefix']:'')
                ->setSuffix((isset($setting['suffix']))?$setting['suffix']:'');
        return $filter->filter($this->getFilename());
    }

    /**
     * @abstract
     * @param $size
     * @return array
     */
    abstract public function getThumbSetting($size);
}
