<?php

class Sch_View_Helper_FormSize extends Zend_View_Helper_FormMultiCheckbox
{

    public function formSize($name, $value = null, $attribs = null,
                             $options = null, $listsep = "<br />\n")
    {
        return parent::formMultiCheckbox($name, $value, $attribs, $options, $listsep);
    }

}
