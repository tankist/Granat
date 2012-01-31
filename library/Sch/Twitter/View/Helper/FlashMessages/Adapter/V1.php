<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Natali
 * Date: 31.01.12
 * Time: 0:58
 * To change this template use File | Settings | File Templates.
 */
class Sch_Twitter_View_Helper_FlashMessages_Adapter_V1
    extends Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
    implements Sch_Twitter_View_Helper_FlashMessages_Adapter_Interface
{

    /**
     * @var string
     */
    protected $_messageBlockStart = '<p>';
    /**
     * @var string
     */
    protected $_messageBlockEnd = '</p>';

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
        return sprintf($this->_wrapperBlockStart, 'alert-message ' . $this->getStatus());
    }

}
