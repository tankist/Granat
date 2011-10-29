<?php

class Skaya_Filter_File_Rename extends Zend_Filter_File_Rename
{

    /**
     * Returns only the new filename without moving it
     * But existing files will be erased when the overwrite option is true
     *
     * @param  string  $value  Full path of file to change
     * @param  boolean $source Return internal informations
     * @return string The new filename which has been set
     */
    public function getNewName($value, $source = false)
    {
        $file = $this->_getFileName($value);
        if ($file['source'] == $file['target']) {
            return $value;
        }

        if (!file_exists($file['source'])) {
            return $value;
        }

        $dir = (!empty($file['target']))?dirname($file['target']):dirname($file['source']);

        if (($file['overwrite'] == true) && (file_exists($file['target']))) {
            unlink($file['target']);
        }

        $ext = pathinfo($file['source'], PATHINFO_EXTENSION);
        if (!$ext && extension_loaded('exif')) {
            $types = array(
                IMAGETYPE_GIF => 'gif',
                IMAGETYPE_JPEG => 'jpg',
                IMAGETYPE_PNG => 'png'
            );
            $filetype = exif_imagetype($file['source']);
            if (array_key_exists($filetype, $types)) {
                $ext = $types[$filetype];
            }
        }

        do
            $file['target'] = $dir . DIRECTORY_SEPARATOR . $this->getId() . ((!empty($ext)) ? ("." . $ext) : '');
        while
        (file_exists($file['target']));

        if ($source) {
            return $file;
        }

        return $file['target'];
    }

    public static function getId()
    {
        $length = 5;
        $num = range(0, 9);
        $alf = range('a', 'z');
        $_alf = range('A', 'Z');
        $symbols = array_merge($num, $alf, $_alf);
        shuffle($symbols);
        $code_array = array_slice($symbols, 0, (int)$length);
        $code = implode("", $code_array);

        return $code;
    }

}
