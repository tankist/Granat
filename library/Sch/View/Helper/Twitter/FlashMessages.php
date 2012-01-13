<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victor
 * Date: 12.12.11
 * Time: 18:36
 * To change this template use File | Settings | File Templates.
 */
class Sch_View_Helper_Twitter_FlashMessages extends Sch_View_Helper_FlashMessages
{

    const DEFAULT_STATUS = 'info';

    protected $_statuses = array('danger', 'error', 'success', 'info');

    /**
     * @access public
     * @return string HTML of output messages
     */
    public function flashMessages()
    {
        // Set up some variables, including the retrieval of all flash messages.
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $statMessages = array();
        $output = '';

        // If there are no messages, don't bother with this whole process.
        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if (!array_key_exists('status', $message) || !in_array($message['status'], $this->_statuses)) {
                    $message['status'] = self::DEFAULT_STATUS;
                }

                $output .= '<div class="alert-message ' . $message['status'] . '"  >';
                $output .= '<p>' . $this->view->translate($message['message']) . '</p>';
                $output .= '</div>';
            }
        }
        return $output;
    }

}
