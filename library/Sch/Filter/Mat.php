<?php
class Sch_Filter_Mat implements Zend_Filter_Interface
{

    /**
     * @var bool
     */
    protected $_isMat = false;

    /**
     * @var array
     */
    protected $_translitMatches = array(
        "a" => "а",
        "c" => "с",
        "e" => "е",
        "k" => "к",
        "m" => "м",
        "o" => "о",
        "x" => "х",
        "y" => "у",
        "ё" => "е"
    );

    /**
     * @var array
     */
    protected $_words = array(
        ".*ху(й|и|я|е|л(и|е)).*",
        ".*пи(з|с)д.*",
        "бля.*",
        ".*бля(д|т|ц).*",
        "(с|сц)ук(а|о|и).*",
        "еб.*",
        ".*уеб.*",
        "заеб.*",
        ".*еб(а|и)(н|с|щ|ц|л).*",
        ".*ебу(ч|щ).*",
        ".*(ъ|ы|ь)?еб(и|к).*",
        ".*пид(о|е|а)р.*",
        ".*хер.*",
        "г(а|о)ндон",
        ".*залуп.*"
    );

    /**
     * @var string
     */
    protected $_replacement;

    public function __construct($replacement = '*****')
    {
        $this->_replacement = $replacement;
        foreach ($this->_words as &$word) {
            $word = iconv('utf-8', 'cp1251', $word);
        }
    }

    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $value = iconv('utf-8', 'cp1251', $value);

        $this->_isMat = false;
        $counter = 0;
        $elems = explode(' ', $value); //here we explode string to words
        $count_elems = count($elems);
        for ($i = 0; $i < $count_elems; $i++) {
            /*formating word...*/
            $str_rep = preg_replace('$[^\w]$i', "", strtolower($elems[$i]));
            for ($j = 0; $j < strlen($str_rep); $j++) {
                foreach ($this->_translitMatches as $k => $v) {
                    if ($str_rep[$j] == $k) {
                        $str_rep[$j] = $v;
                    }

                }
            }
            /*done*/

            /*here we are trying to find bad word*/
            /*match in the special array*/
            for ($k = 0; $k < count($this->_words); $k++) {
                if (preg_match("/\*$/i", $this->_words[$k])) {
                    if (preg_match("/^" . $this->_words[$k] . '/i', $str_rep)) {
                        $elems[$i] = '<span class="mat" title="' . $elems[$i] . '">' . $this->getReplacement() . '</span>';
                        $counter++;
                        break;
                    }
                }
                if ($str_rep == $this->_words[$k]) {
                    $elems[$i] = '<span class="mat" title="' . $elems[$i] . '">' . $this->getReplacement() . '</span>';
                    $counter++;
                    break;
                }
            }
        }
        if ($counter != 0) {
            $value = implode(" ", $elems);
            $this->_isMat = true;
        } //here we implode words in the whole string

        $value = iconv('cp1251', 'utf-8', $value);

        return $value;
    }

    /**
     * @return bool
     */
    public function isMat()
    {
        return $this->_isMat;
    }

    /**
     * @param $replacement
     * @return Sch_Filter_Mat
     */
    public function setReplacement($replacement)
    {
        $this->_replacement = $replacement;
        return $this;
    }

    /**
     * @return string
     */
    public function getReplacement()
    {
        return $this->_replacement;
    }

}
