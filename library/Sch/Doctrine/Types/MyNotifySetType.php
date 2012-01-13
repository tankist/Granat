<?php
namespace Sch\Doctrine\Types;


use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * My custom datatype.
 * хранится в виде ассоциативного массива с ключами соответствующими значениям set
 */
class MyNotifySetType extends Type
{
    const MYTYPE = 'mynotifyset'; // modify to match your type name

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // return the SQL used to create your column type. To create a portable column type, use the $platform.
        return 'SET("snovsoob","snpostblog","snpobsch","snpbkg","snpbkp","snpgur","snpgn","snpga","snpgs","snoizbm","sdizb","pkpost","pkponrpost","snos","prekl")';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        // This is executed when the value is read from the database. Make your conversions here, optionally using the $platform.
        $arr = array();
        if ($value <> '') {
            $ar2 = explode(',', $value);
            foreach ($ar2 as $val) {
                //        		$s = substr($val,1,strlen($val)-2);
                $arr[$val] = 1;
            }
        }
        return $arr;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        // This is executed when the value is written to the database. Make your conversions here, optionally using the $platform.
        $arr = array();
        foreach ($value as $key => $val) {
            $arr[] = $key;
        }
        $s = join(',', $arr);
        return $s;
    }

    public function getName()
    {
        return self::MYTYPE; // modify to match your constant name
    }
}
