<?php

class Sch_Filter_Blog_Tags implements Zend_Filter_Interface
{

    public function filter($value)
    {
        $lines = preg_split('$[\r\n]+$i', $value);
        foreach ($lines as &$line) {
            $_line = trim($line);
            if (!empty($_line)) {
                if (substr($_line, 0, 1) != '<' ||
                    substr($_line, strlen($_line) - 1, 1) != '>'
                ) {
                    $line = '<p>' . $_line . '</p>';
                }
            }
            else {
                $line = $_line;
            }
        }
        return join("\n", array_filter($lines));
    }

}
