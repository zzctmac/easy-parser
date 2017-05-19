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
    const ARRAY_ = 5;

    /**
     * @var string|ImportClass
     */
    public $type;
    public $name;

    /**
     * Arg constructor.
     * @param $type
     * @param $name
     */
    public function __construct($type, $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public static function create($type, $name)
    {
        return new self($type, $name);
    }



}