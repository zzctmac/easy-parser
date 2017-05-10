<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 16:04
 */

namespace st\bean;


class Namespace_
{
    public $name;

    /**
     * Namespace_ constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public static function create($name)
    {
        return new self($name);
    }
}