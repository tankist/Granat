<?php
class Skaya_Controller_Action_Helper_ImageNewsPath extends Zend_Controller_Action_Helper_Abstract
{

    public function direct(Model_News $news = null)
    {
        $root = realpath($this->getRequest()->getServer('DOCUMENT_ROOT')) . DIRECTORY_SEPARATOR;
        if ($news && !$news->isEmpty()) {
            $path = join('/', array('uploads', 'news', $news->id));
        } else {
            $path = join('/', array('uploads', 'temp', 'news'));
        }
        if (!is_dir($root . $path)) {
            mkdir($root . $path, 0777, true);
        }
        return $path;
    }

}
