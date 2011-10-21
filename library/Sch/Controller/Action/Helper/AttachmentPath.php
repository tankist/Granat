<?php

class Sch_Controller_Action_Helper_AttachmentPath extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @param Skaya_Model_Abstract $entity
     * @param null $entitySubdir
     * @return string
     */
    public function getRealPath($entity, $entitySubdir = null)
    {
        $path = $this->getRequest()->getServer('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR;
        if (($entity instanceof Skaya_Model_Abstract) && ($id = $entity->getId())) {
            $path .= 'uploads' . DIRECTORY_SEPARATOR;
            if ($entitySubdir) {
                $path .= $entitySubdir . DIRECTORY_SEPARATOR;
            }
            $path .= $id . DIRECTORY_SEPARATOR;
        } else {
            $path .= 'uploads' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . md5(time()) . DIRECTORY_SEPARATOR;
        }
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return realpath($path);
    }

    /**
     * @param Skaya_Model_Abstract $entity
     * @param null $entitySubdir
     * @return string
     */
    public function getWebPath($entity, $entitySubdir = null)
    {
        $path = '/';
        if (($entity instanceof Skaya_Model_Abstract) && ($id = $entity->getId())) {
            $path .= 'uploads' . '/';
            if ($entitySubdir) {
                $path .= $entitySubdir . '/';
            }
            $path .= $id . '/';
        } else {
            $path .= 'uploads' . '/' . 'temp' . '/' . md5(time()) . '/';
        }
        return $path;
    }

    /**
     * @param Skaya_Model_Abstract $entity
     * @param null $entitySubdir
     * @return string
     */
    public function direct($entity, $entitySubdir = null)
    {
        return $this->getRealPath($entity, $entitySubdir);
    }

}

?>
