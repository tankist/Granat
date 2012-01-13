<?php

use \Entities\User;

class Sch_Controller_Action_Helper_Avatar extends Zend_Controller_Action_Helper_Abstract
{

    public function upload($file, User $user, $uploadPath = null)
    {
        $pathHelper = new Sch_Controller_Action_Helper_AttachmentPath();
        $avatarPath = $pathHelper->getRealPath($user, 'user');

        if (!$uploadPath) {
            $uploadPath = $avatarPath;
        }

        //Создаем фильтр для переименования файлов
        $renameFilter = new Zend_Filter_File_Rename($avatarPath);

        $avatarFile = $renameFilter->setFile(
            array(
                'target' => $avatarPath . DIRECTORY_SEPARATOR . $file,
                'overwrite' => true
            )
        )->filter($uploadPath . DIRECTORY_SEPARATOR . $file);

        $thumbnailtFilenameFilter = new Sch_Filter_ThumbFilename();
        $files = array();
        $thumbed = false;
        foreach (User::$userpicSettings as $size => $setting) {
            $thumbnailtFilenameFilter
                ->setPrefix((isset($setting['prefix'])) ? $setting['prefix'] : '')
                ->setSuffix((isset($setting['suffix'])) ? $setting['suffix'] : '')
                ->setFilename($user->getLogin());

            $thumbFile = $thumbnailtFilenameFilter->filter($avatarFile);

            if ($thumbFile != $avatarFile) {
                if (file_exists($thumbFile)) {
                    @unlink($thumbFile);
                }
                @copy($avatarFile, $thumbFile);
            }


            $thumbnailFilter = new ZFEngine_Filter_File_ImageResize($setting);
            $files[$size] = $thumbnailFilter->filter($thumbFile);
            $thumbed = ($thumbed || !!$files[$size]);
        }

        if ($thumbed) {
            @unlink($avatarFile);
        }

        return $files;
    }

}
