<?php

class Sch_Filter_Cut implements Zend_Filter_Interface
{

    const SYMBOLS_PER_LINE = 150;

    protected $_lines = 5;

    public function __construct($lines = null)
    {
        if (!empty($lines)) {
            $this->setLines($lines);
        }
    }

    public function filter($value)
    {
        $clearedText = trim(strip_tags($value));
        $maxLen = $this->_getMaxLength();
        if (mb_strlen($clearedText) > $maxLen) {
            $strippedTagsStrMatchesCount = preg_match_all('/(<.+?>|[^<>]+)/i', $value, $m, PREG_PATTERN_ORDER);
            if ($strippedTagsStrMatchesCount > 0) {
                $strippedTagsStrMatches = $m[0];
                $totalLen = $tagsLen = 0;
                $newStr = '';
                foreach ($strippedTagsStrMatches as $_m) {
                    if (strpos($_m, '<') === false) {
                        $totalLen += mb_strlen($_m);
                    }
                    else {
                        $tagsLen += mb_strlen($_m);
                    }
                    $newStr .= $_m;
                    if ($totalLen > $maxLen - 3) {
                        break;
                    }
                }
                if ($totalLen > $maxLen - 3) {
                    $value = mb_substr($value, 0, $maxLen + $tagsLen);
                    $value = $this->_restoreTags($value . '...');
                }
            }
            else {
                $value = '';
            }
        }
        return $value;
    }

    public function setLines($lines)
    {
        $this->_lines = $lines;
        return $this;
    }

    public function getLines()
    {
        return $this->_lines;
    }

    protected function _getMaxLength()
    {
        return self::SYMBOLS_PER_LINE * $this->getLines();
    }

    protected function _restoreTags($input)
    {
        $opened = $closed = array();
        if (preg_match_all("/<(\/?[a-z]+)>/i", $input, $matches)) {
            foreach ($matches[1] as $tag) {
                if (preg_match("/^[a-z]+$/i", $tag, $regs)) {
                    $opened[] = $regs[0];
                } elseif (preg_match("/^\/([a-z]+)$/i", $tag, $regs)) {
                    $closed[] = $regs[1];
                }
            }
        }
        // use closing tags to cancel out opened tags
        if ($closed) {
            foreach ($opened as $idx => $tag) {
                foreach ($closed as $idx2 => $tag2) {
                    if ($tag2 == $tag) {
                        unset($opened[$idx]);
                        unset($closed[$idx2]);
                        break;
                    }
                }
            }
        }
        // close tags that are still open
        if ($opened) {
            $tagstoclose = array_reverse($opened);
            foreach ($tagstoclose as $tag) {
                $input .= "</$tag>";
            }
        }
        return $input;
    }

}