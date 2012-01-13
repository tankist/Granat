<?php

class Sch_Filter_File_Rename extends Zend_Filter_File_Rename
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

        if (($file['overwrite'] == true) && (file_exists($file['target']))) {
            unlink($file['target']);
        }

        $ext = pathinfo($file['source'], PATHINFO_EXTENSION);

        do
            $file['target'] = dirname($file['target']) .
                DIRECTORY_SEPARATOR .
                $this->getId() . "." . strtolower($ext);
        while
        (file_exists($file['target']));

        if ($source) {
            return $file;
        }

        return $file['target'];
    }

    public function getId()
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
