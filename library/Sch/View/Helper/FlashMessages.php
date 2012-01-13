<?php
/**
 * FlashMessages view helper
 * application/modules/admin/views/helpers/FlashMessages.php
 *
 * This helper creates an easy method to return groupings of
 * flash messages by status.
 *
 * @author Aaron Bach <bachya1208[at]googlemail.com
 * @license Free to use - no strings.
 */
class Sch_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{
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
            foreach ($messages as $message)
            {
                if (!array_key_exists($message['status'], $statMessages))
                    $statMessages[$message['status']] = array();

                array_push($statMessages[$message['status']], $this->view->translate($message['message']));
            }

            // This chunk of code formats messages for HTML output (per
            // the example in the class comments).
            foreach ($statMessages as $status => $messages)
            {
                $output .= '<div class="alert-message messages ' . $status . '"  >';
                $output .= '<ul>';
                foreach ($messages as $message)
                    $output .= '<li>' . $message . '</li>';
                $output .= '</ul>';

                $output .= '</div>';
            }
        }
        return $output;
    }
}
