<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-18
 * Time: 10:09
 */

namespace st\bean;


class Attr extends Variable
{
    public $defaultValue;
    public $visitType;

    const PUBLIC_TYPE = 1;
    const PROTECTED_TYPE = 2;
    const PRIVATE_TYPE = 4;

    public static function create($name, $type, $isObject, $visitType = self::PUBLIC_TYPE, $defaultValue = null)
    {
        $ins =  parent::create($name, $type, $isObject);
        $ins->defaultValue = $defaultValue;
        $ins->visitType = $visitType;
        return $ins;
    }


}