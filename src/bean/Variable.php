<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-9
 * Time: 14:48
 */

namespace st\bean;


use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar;

class Variable
{
    public $name;
    public $type;
    public $isObject;

    const CURRENT_CLASS = 1;

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

    /**
     * @param $name
     * @param $type
     * @param $isObject
     * @return static
     */
    public static function create($name, $type, $isObject)
    {
        return new static($name, $type, $isObject);
    }

    public static function getTypeByScalar( $node)
    {
        if($node instanceof Scalar) {
            switch (get_class($node)) {
                case Scalar\LNumber::class:
                case Scalar\DNumber::class:
                    $type = 'number';
                    break;
                case Scalar\String_::class:
                    $type = 'string';
                    break;
                default:
                    $type = null;
                    break;
            }
            return $type;
        }
        if($node instanceof Array_)
            return 'array';
        return null;
    }

    public static function createThis()
    {
        return self::create('this', self::CURRENT_CLASS, true);
    }
}