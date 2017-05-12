<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-9
 * Time: 12:09
 */

namespace st\bean;


use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar;
use st\parse\IBase;

class Call
{
    public $isMethod;
    /**
     * @var ImportClass
     */
    public $class;
    public $isStatic;
    public $object;
    public $isNew;
    public $name;
    /**
     * @var Arg[]
     */
    public $args;

    /**
     * Call constructor.
     * @param $isMethod
     * @param $class ImportClass
     * @param $isStatic
     * @param $object
     * @param $isNew
     * @param $name
     * @param $args
     */
    public function __construct($isMethod, $class, $isStatic, $object, $isNew, $name, $args)
    {
        $this->isMethod = $isMethod;
        $this->class = $class;
        $this->isStatic = $isStatic;
        $this->object = $object;
        $this->isNew = $isNew;
        $this->name = $name;
        $this->args = $args;
    }

    /**
     * @param $isMethod
     * @param $class ImportClass
     * @param $isStatic
     * @param $object
     * @param $isNew
     * @param $name
     * @param $args
     * @return Call
     */
    public static function create($isMethod, $class, $isStatic, $object, $isNew, $name, $args)
    {
        return new self($isMethod, $class, $isStatic, $object, $isNew, $name, $args);
    }

    /**
     * @param $class ImportClass
     * @param $args
     * @param $name
     * @return Call
     */
    public static function createByNew($class, $args, $name = null)
    {
        return self::create(false, $class, false, null, true, $class ? $class->name:$name, $args);
    }

    public static function createByMethodCall($class, $object, $name, $args)
    {
        return self::create(true, $class, false, $object, false, $name, $args);
    }



}