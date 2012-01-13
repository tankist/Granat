<?php

use \Entities\AbstractEntity;

class Sch_Controller_Action_Helper_AttachmentPath extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @param null $entity
     * @param null $entitySubdir
     * @param bool $relative
     * @return string
     */
    public function getRealPath($entity = null, $entitySubdir = null, $relative = false)
    {
        $path = ($relative) ? '' : $this->getRequest()->getServer('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR;
        if (($entity instanceof AbstractEntity) && ($id = $entity->getId())) {
            $path .= 'files' . DIRECTORY_SEPARATOR;
            if ($entity instanceof \Entities\Attachable) {
                $path .= $entity->getAttachmentsPath();
            }
            else {
                if ($entitySubdir) {
                    $path .= $entitySubdir . DIRECTORY_SEPARATOR;
                }
                $path .= $id . DIRECTORY_SEPARATOR;
            }
        } else {
            $path .= 'files' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . md5(time()) . DIRECTORY_SEPARATOR;
        }
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return realpath($path);
    }

    /**
     * @param AbstractEntity $entity
     * @param null $entitySubdir
     * @return string
     */
    public function getWebPath($entity, $entitySubdir = null)
    {
        $path = '/';
        if (($entity instanceof AbstractEntity) && ($id = $entity->getId())) {
            $path .= 'files' . '/';
            if ($entity instanceof \Entities\Attachable) {
                $path .= $entity->getAttachmentsPath() . '/';
            }
            else {
                if ($entitySubdir) {
                    $path .= $entitySubdir . '/';
                }
                $path .= $id . '/';
            }
        } else {
            $path .= 'files' . '/' . 'temp' . '/' . md5(time()) . '/';
        }
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }

    public function getRelativePath($fullPath)
    {
        $root = str_replace(DIRECTORY_SEPARATOR, '/', $this->getRequest()->getServer('DOCUMENT_ROOT'));
        return str_replace(array(DIRECTORY_SEPARATOR, $root), array('/', ''), $fullPath);
    }

    /**
     * @param AbstractEntity $entity
     * @param null $entitySubdir
     * @return string
     */
    public function direct($entity = null, $entitySubdir = null)
    {
        return $this->getRealPath($entity, $entitySubdir);
    }

}

?>
