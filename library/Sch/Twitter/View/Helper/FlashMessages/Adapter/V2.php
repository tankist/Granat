<?php

/**
 * @class
 */
class Sch_Twitter_View_Helper_FlashMessages_Adapter_V2
    extends Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
    implements Sch_Twitter_View_Helper_FlashMessages_Adapter_Interface
{

    /**
     * @var string
     */
    protected $_wrapperBlockStart = '<div class="%s">';
    /**
     * @var string
     */
    protected $_wrapperBlockEnd = '</div>';

    /**
     * @return string
     */
    public function getWrapperBlockStart()
    {
        return sprintf($this->_wrapperBlockStart, 'alert alert-' . $this->getStatus());
    }

}
