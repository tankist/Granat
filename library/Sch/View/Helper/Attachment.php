<?php

use \Entities\AbstractAttachment, \Entities\AbstractEntity;

class Sch_View_Helper_Attachment extends Zend_View_Helper_Abstract
{

    public function attachment(AbstractAttachment $attachment, $attributes = array())
    {
        $attachmentHtml = '';
        $filename = $attachment->getFilename();
        $type = strtolower($attachment->getType());
        $realPath = $this->realPath($attachment);
        $webPath = $this->webPath($attachment);
        if (!file_exists($realPath . DIRECTORY_SEPARATOR . $filename)) {
            return '';
        }
        if (in_array($type, array('gif', 'jpeg', 'jpg', 'png'))) {
            $thumbnail = pathinfo($filename, PATHINFO_FILENAME) . '_m.' . pathinfo($filename, PATHINFO_EXTENSION);
            if (!file_exists($realPath . DIRECTORY_SEPARATOR . $thumbnail)) {
                $thumbnail = $filename;
            }
            list($width) = getimagesize($realPath . DIRECTORY_SEPARATOR . $thumbnail);
            if ($width > 400) {
                $width = 400;
            }
            $attachmentHtml = sprintf('<p class="attachment image"><a class="%s" href="%s" rel="facebox"
            title="%s"><img src="%s" alt="%s" width="%d"></a></p>',
                $type, $webPath . '/' . $filename, $filename,
                $webPath . '/' . $thumbnail, $filename, $width);
        }
        else {
            $filesize = filesize($realPath . DIRECTORY_SEPARATOR . $filename);
            $attachmentHtml = sprintf('<p class="attachment"><a class="%s" href="%s" title="%s">%s</a></p>',
                $type, $webPath . '/' . $filename, $filename, $this->_formatFileSize($filesize));
        }
        return $attachmentHtml;
    }

    public function realPath(AbstractAttachment $attachment)
    {
        /**
         * @var Sch_Controller_Action_Helper_AttachmentPath $attachmentsPathHelper
         */
        $attachmentsPathHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('AttachmentPath');
        return $attachmentsPathHelper->getRealPath($attachment->getContainerEntity());
    }

    public function webPath(AbstractAttachment $attachment)
    {
        /**
         * @var Sch_Controller_Action_Helper_AttachmentPath $attachmentsPathHelper
         */
        $attachmentsPathHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('AttachmentPath');
        return $attachmentsPathHelper->getWebPath($attachment->getContainerEntity());
    }

    protected function _formatFileSize($filesize)
    {
        $bytes = array('b', 'kb', 'Mb', 'Gb');
        $i = 0;
        while (($filesize << 10) > 0) {
            $i++;
            $filesize = floor($filesize / 1024);
        }
        if ($i > count($bytes)) {
            $i = count($bytes) - 1;
        }
        return sprintf('%d, %s', $filesize, $bytes[$i]);
    }

}
