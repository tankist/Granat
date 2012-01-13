<?php

use \Entities\AbstractPhoto;

class Sch_View_Helper_Photo extends Zend_View_Helper_HtmlElement
{

    public function photo(AbstractPhoto $photo, $size, $attribs = array())
    {
        $attachmentsHelper = new Sch_Controller_Action_Helper_AttachmentPath();
        $entity = $photo->getContainerEntity();
        $www = $attachmentsHelper->getWebPath($entity);
        $path = $attachmentsHelper->getRealPath($entity);
        $file = $photo->getThumbnailFilename($size);
        $image = $www . $file;
        $thumbSettings = $photo->getThumbSetting($size);
        if ($thumbSettings) {
            if (array_key_exists('width', $thumbSettings)) {
                $attribs['width'] = $thumbSettings['width'];
            }
            if (array_key_exists('height', $thumbSettings)) {
                $attribs['height'] = $thumbSettings['height'];
            }
        }
        if (!file_exists($path . DIRECTORY_SEPARATOR . $file)) {
            $file = $photo->getFilename();
            $image = $www . $file;
            if (!file_exists($path . DIRECTORY_SEPARATOR . $file) &&
                $thumbSettings && array_key_exists('empty', $thumbSettings)
            ) {
                $image = $thumbSettings['empty'];
            }
        }
        return $this->_renderImage($image, $attribs);
    }

    protected function _renderImage($image, $attribs = array())
    {
        if (array_key_exists('__clear_cache', $attribs) && $attribs['__clear_cache']) {
            $image .= '?' . uniqid();
            unset($attribs['__clear_cache']);
        }
        return '<img' .
            ' src="' . $image . '"' .
            $this->_htmlAttribs($attribs) . ' ' .
            $this->getClosingBracket();
    }

}
