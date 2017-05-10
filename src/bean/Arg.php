<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-9
 * Time: 15:49
 */

namespace st\bean;




class Arg
{
    const SCALAR = 1;
    const STATIC_CALL = 2;
    const OP = 3;
    const METHOD_CALL = 4;

    public $type;
    public $name;
}