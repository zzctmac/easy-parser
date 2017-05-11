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
    public $isObject;

    /**
     * Variable constructor.
     * @param $name
     * @param $type
     * @param $isObject
     */
    public function __construct($name, $type, $isObject)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isObject = $isObject;
    }

    public static function create($name, $type, $isObject)
    {
        return new self($name, $type, $isObject);
    }
}