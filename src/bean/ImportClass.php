<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-9
 * Time: 11:14
 */

namespace st\bean;


class ImportClass
{
    public $name;
    public $alias;

    /**
     * ImportClass constructor.
     * @param $name
     * @param $alias
     */
    public function __construct($name, $alias)
    {
        $this->name = $name;
        $this->alias = $alias;
    }

    public static function create($name, $alias)
    {
        return new self($name, $alias);
    }


}