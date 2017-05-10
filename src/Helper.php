<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 16:01
 */

namespace st;


class Helper
{
    public static function getNamespaceFromParts($parts)
    {
        return implode("\\", $parts);
    }

}