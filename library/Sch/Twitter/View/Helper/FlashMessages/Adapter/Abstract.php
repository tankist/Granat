<?php

/**
 *
 */
abstract class Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
    implements Sch_Twitter_View_Helper_FlashMessages_Adapter_Interface
{

    const TWITTER_BOOTSTRAP_STATUS_INFO = 'info';
    const TWITTER_BOOTSTRAP_STATUS_SUCCESS = 'success';
    const TWITTER_BOOTSTRAP_STATUS_DANGER = 'danger';
    const TWITTER_BOOTSTRAP_STATUS_ERROR = 'error';

    /**
     * @var string
     */
    protected $_message = '';

    /**
     * @var string
     */
    protected $_status = '';

    /**
     * @var string
     */
    protected $_messageBlockStart = '';
    /**
     * @var string
     */
    protected $_messageBlockEnd = '';

    /**
     * @var string
     */
    protected $_wrapperBlockStart = '';
    /**
     * @var string
     */
    protected $_wrapperBlockEnd = '';

    /**
     * @static
     * @param $version
     * @return Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
     * @throws Sch_Twitter_View_Helper_FlashMessages_Adapter_Exception
     */
    public static function factory($version)
    {
        $className = 'Sch_Twitter_View_Helper_FlashMessages_Adapter_' . ucfirst($version);
        if (!class_exists($className, true)) {
            throw new Sch_Twitter_View_Helper_FlashMessages_Adapter_Exception('Adapter "' . $version .'" not found');
        }
        return new $className();
    }

    /**
     * @param $message
     * @return Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param $status
     * @return Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
     */
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @return string
     */
    public function render()
    {
        return sprintf('%s%s%s%s%s',
            $this->getWrapperBlockStart(),
            $this->getMessageBlockStart(),
            $this->getMessage(),
            $this->getMessageBlockEnd(),
            $this->getWrapperBlockEnd()
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param $messageBlockEnd
     * @return \Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
     */
    public function setMessageBlockEnd($messageBlockEnd)
    {
        $this->_messageBlockEnd = $messageBlockEnd;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageBlockEnd()
    {
        return $this->_messageBlockEnd;
    }

    /**
     * @param $messageBlockStart
     * @return Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
     */
    public function setMessageBlockStart($messageBlockStart)
    {
        $this->_messageBlockStart = $messageBlockStart;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageBlockStart()
    {
        return $this->_messageBlockStart;
    }

    /**
     * @param $wrapperBlockEnd
     * @return Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
     */
    public function setWrapperBlockEnd($wrapperBlockEnd)
    {
        $this->_wrapperBlockEnd = $wrapperBlockEnd;
        return $this;
    }

    /**
     * @return string
     */
    public function getWrapperBlockEnd()
    {
        return $this->_wrapperBlockEnd;
    }

    /**
     * @param $wrapperBlockStart
     * @return Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract
     */
    public function setWrapperBlockStart($wrapperBlockStart)
    {
        $this->_wrapperBlockStart = $wrapperBlockStart;
        return $this;
    }

    /**
     * @return string
     */
    public function getWrapperBlockStart()
    {
        return $this->_wrapperBlockStart;
    }

}
