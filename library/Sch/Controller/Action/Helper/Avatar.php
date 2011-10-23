<?php

class Sch_Controller_Action_Helper_Avatar extends Zend_Controller_Action_Helper_Abstract
{

    public function upload($file, $uploadPath = null)
    {
        //Создаем фильтр для переименования файлов
        $renameFilter = new Zend_Filter_File_Rename($uploadPath);

        $avatarFile = $renameFilter->setFile(
            array(
                 'target' => $uploadPath . DIRECTORY_SEPARATOR . $file,
                 'overwrite' => true
            )
        )->filter($uploadPath . DIRECTORY_SEPARATOR . $file);

        $thumbnailtFilenameFilter = new Sch_Filter_ThumbFilename();
        $files = array();
        $thumbed = false;
        foreach (Model_ModelPhoto::getThumbnailPack() as $size => $setting) {
            if ($setting['indication_type'] == Model_ModelPhoto::INDICATION_PREFIX) {
                $thumbnailtFilenameFilter->setPrefix($setting['indication']);
            }
            if ($setting['indication_type'] == Model_ModelPhoto::INDICATION_SUFFIX) {
                $thumbnailtFilenameFilter->setSuffix($setting['indication']);
            }
            $thumbFile = $thumbnailtFilenameFilter->filter($avatarFile);

            if ($thumbFile != $avatarFile) {
                if (file_exists($thumbFile)) {
                    @unlink($thumbFile);
                }
                @copy($avatarFile, $thumbFile);
            }


            $thumbnailFilter = new ZFEngine_Filter_File_ImageResize($setting);
            $files[$size] = $thumbnailFilter->filter($thumbFile);
        }

        return $files;
    }

}
