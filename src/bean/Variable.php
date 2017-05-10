<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-9
 * Time: 14:48
 */

namespace st\bean;


class Variable
{
    public $name;
    public $type;

    /**
     * Variable constructor.
     * @param $name
     * @param $type
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public static function create($name, $type)
    {
        return new self($name, $type);
    }
}