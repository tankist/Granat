<?php
class Sch_Controller_Action_Helper_Attachments extends Zend_Controller_Action_Helper_Abstract
{

    public function upload($files, $attachmentsPath, $attachmentEntityName, $uploadPath = null, $useAutoname = false, $autoThumbnail = false)
    {
        if (!$uploadPath) {
            $uploadPath = $attachmentsPath;
        }
        $attachments = array();
        //Создаем фильтр для переименования файлов
        $renameFilter = new Sch_Filter_File_Rename($attachmentsPath);
        $translitFilter = new Sch_Filter_Translit();
        foreach ((array)$files as $attachmentFile) {
            /**
             * Создаем вложение
             * @var \Entities\AbstractAttachment $attachment
             */
            $attachment = new $attachmentEntityName();
            //Переименовываем файл вложения...
            $newAttachmentFile = (!$useAutoname) ? $translitFilter->filter($attachmentFile) : '';
            $attachmentFile = $renameFilter->setFile(
                array(
                    'target' => $attachmentsPath . DIRECTORY_SEPARATOR . $newAttachmentFile,
                    'overwrite' => true
                )
            )->filter($uploadPath . DIRECTORY_SEPARATOR . $attachmentFile);
            //Задаем имя файла вложения...
            $attachment->setFilename(basename($attachmentFile));
            //Если картинка, то нужно сделать уменьшенную копию
            if ($autoThumbnail && in_array(strtolower($attachment->getType()), array('gif', 'jpeg', 'jpg', 'png'))) {
                $this->thumbnail(array($attachment), $attachmentsPath);
            }
            $attachments[] = $attachment;
        }
        return $attachments;
    }

    public function uploadPhoto($files, $attachmentsPath, $attachmentEntityName)
    {
        $attachments = array();
        //Создаем фильтр для переименования файлов
        $renameFilter = new Sch_Filter_File_Rename($attachmentsPath);
        /**
         * Создаем вложение
         * @var \Entities\AbstractAttachment $attachment
         */
        $entity = new ReflectionClass($attachmentEntityName);
        foreach ((array)$files as $attachmentFile) {
            $attachment = $entity->newInstance();
            $attachmentFile = $renameFilter->setFile(
                array(
                    'target' => $attachmentsPath .
                        DIRECTORY_SEPARATOR .
                        md5(uniqid('img', true)) . '.' .
                        pathinfo($attachmentFile, PATHINFO_EXTENSION),
                    'overwrite' => true
                )
            )->filter($attachmentsPath . DIRECTORY_SEPARATOR . $attachmentFile);
            //Задаем имя файла вложения...
            $attachment->setFilename(basename($attachmentFile));
            $attachments[] = $attachment;
        }
        return $attachments;
    }

    public function thumbnail($photos, $source, $target = null, $size = array())
    {
        if (!is_array($photos)) {
            $photos = array($photos);
        }
        if (!is_dir($source)) {
            throw new Zend_Controller_Action_Exception('Path not found', 500);
        }
        if (!is_dir($target)) {
            $result = @mkdir($target, 0777, true);
            if (!$result) {
                $target = $source;
            }
        }
        $thumbnailtFilenameFilter = new Sch_Filter_ThumbFilename();
        $files = array();
        foreach ($photos as /** @var $photo \Entities\AbstractPhoto */
            &$photo) {
            if (!($photo instanceof \Entities\AbstractPhoto)) {
                throw new Zend_Controller_Action_Exception('Unable to thumbnail non-image attachments', 500);
            }
            /**
             * Создаем вложение
             * @var \Entities\AbstractPhoto $attachment
             */
            $entity = new ReflectionObject($photo);
            $thumbnails = array();
            if ($thumbSettings = $entity->getStaticPropertyValue('thumbSettings')) {
                if (empty($size) || !is_array($size)) {
                    $size = $thumbSettings;
                }
                foreach ($size as $setting) {
                    if (is_string($setting)) {
                        if (!array_key_exists($setting, $thumbSettings)) {
                            continue;
                        }
                        $setting = $thumbSettings[$setting];
                    }
                    $thumbnailtFilenameFilter
                        ->setPrefix((isset($setting['prefix'])) ? $setting['prefix'] : '')
                        ->setSuffix((isset($setting['suffix'])) ? $setting['suffix'] : '');

                    $thumbFile = $thumbnailtFilenameFilter->filter($photo->getFilename());
                    $sourcePath = $source . DIRECTORY_SEPARATOR . $photo->getFilename();
                    $targetPath = $target . DIRECTORY_SEPARATOR . $thumbFile;

                    if ($targetPath != $sourcePath) {
                        if (file_exists($targetPath)) {
                            @unlink($targetPath);
                        }
                        @copy($sourcePath, $targetPath);
                    }

                    $thumbnailFilter = new ZFEngine_Filter_File_ImageResize($setting);
                    $thumbnails[] = $thumbnailFilter->filter($targetPath);
                }
            }

            $files[] = array(
                'file' => $photo,
                'thumbnails' => $thumbnails
            );
        }

        return $files;
    }

    public function recursiveClearFolder($path)
    {
        if (!is_dir($path)) {
            throw new Zend_Controller_Action_Exception('Path not found', 505);
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );
        foreach ($iterator as /** @var $file SplFileInfo */
                 $file) {
            @unlink($file->getPathname());
        }
        @rmdir($path);
    }

}
