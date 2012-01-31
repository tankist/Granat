<?php
/**
 * @class Sch_Twitter_View_Helper_FlashMessages
 */
class Sch_Twitter_View_Helper_FlashMessages extends Sch_View_Helper_FlashMessages
{

    /**
     * @const string
     */
    const DEFAULT_STATUS = 'info';

    /**
     * @const string
     */
    const TWITTER_VERSION_1 = 'v1';

    /**
     * @const string
     */
    const TWITTER_VERSION_2 = 'v2';

    /**
     * @var string
     */
    protected static $_version = self::TWITTER_VERSION_1;

    /**
     * @access public
     * @return string HTML of output messages
     */
    public function flashMessages()
    {
        // Set up some variables, including the retrieval of all flash messages.
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $output = '';

        // If there are no messages, don't bother with this whole process.
        if (count($messages) > 0) {
            foreach ($messages as $message) {
                $status = (array_key_exists('status', $message))?$message['status']:self::DEFAULT_STATUS;
                $message = (array_key_exists('message', $message))?$message['message']:'';
                $adapter = Sch_Twitter_View_Helper_FlashMessages_Adapter_Abstract::factory(self::getVersion());
                $output .= $adapter->setMessage($message)->setStatus($status)->render();
            }
        }
        return $output;
    }

    /**
     * @static
     * @param $version
     * @throws Zend_View_Exception
     */
    public static function setVersion($version)
    {
        self::$_version = $version;
    }

    /**
     * @static
     * @return string
     */
    public static function getVersion()
    {
        return self::$_version;
    }

}
