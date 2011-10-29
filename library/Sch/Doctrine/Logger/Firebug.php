<?php
/**
 * Doctrine logger for FireBug
 *
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @author Magnus Andersson <mange@mange.name>
 */

namespace Sch\Doctrine\Logger;

use Doctrine\DBAL\Logging\SqlLogger;

class Firebug implements SqlLogger
{
    /**
     * The original label for this profiler.
     * @var string
     */
    protected $_label = null;
    /**
     * The label template for this profiler
     * @var string
     */
    protected $_label_template = '%label% (%totalCount% @ %totalDuration% sec)';
    /**
     * The message envelope holding the profiling summary
     * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
     */
    protected $_message = null;
    /**
     * @var float
     */
    protected $_time;
    /**
     * @var String
     */
    protected $_sql;
    /**
     * @var array
     */
    protected $_params;
    /**
     * @var array
     */
    protected $_types;
    /**
     * @var int
     */
    protected $_totalElapsedTime = 0;
    /**
     * @var int
     */
    protected $_totalNumberQueries = 0;

    public function __construct($label = null)
    {
        if (null == $label) {
            $label = "Doctrine logger";
        }
        $this->_label = $label;
        $this->_message = new \Zend_Wildfire_Plugin_FirePhp_TableMessage($this->_label);
        $this->_message->setBuffered(true);
        $this->_message->setHeader(array('Time', 'Query', 'Parameters'));
        $this->_message->setDestroy(true);
        $this->_message->setOption('includeLineNumbers', false);
        \Zend_Wildfire_Plugin_FirePhp::getInstance()->send($this->_message);
    }

    protected function _microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /* (non-PHPdoc)
    * @see Doctrine\DBAL\Logging.SQLLogger::startQuery()
    */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->_time = $this->_microtime_float();
        $this->_sql = $sql;
        $this->_params = $params;
        $this->_types = $types;
    }

    /**
     * Update the label of the message holding the profile info.
     *
     * @return void
     */
    protected function updateMessageLabel()
    {
        if (!$this->_message) {
            return;
        }
        $msg = str_replace(
            array('%label%', '%totalCount%', '%totalDuration%'),
            array($this->_label, $this->_totalNumberQueries, (string)round($this->_totalElapsedTime, 5)),
            $this->_label_template);
        $this->_message->setLabel($msg);
    }

    /* (non-PHPdoc)
    * @see Doctrine\DBAL\Logging.SQLLogger::stopQuery()
    */
    public function stopQuery()
    {
        $this->_message->setDestroy(false);
        $time = $this->_microtime_float() - $this->_time;
        $this->_totalElapsedTime += $time;
        $this->_totalNumberQueries++;
        $this->_message->addRow(array(
                                     (string)round($time, 5),
                                     $this->_sql,
                                     $this->_params)
        );

        $this->updateMessageLabel();
    }
}