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
    public $class;
    public $isStatic;
    public $object;
    public $name;
    public $args;

    /**
     * @param $call MethodCall
     * @param $parse IBase
     * @return self[]
     */
    public static function createFromExprMethodCall($call, $parse)
    {
        $res = [];
        $that = new self();
        $that->isStatic = false;
        $that->isMethod = true;
        $that->name = $call->name;
        $that->args = self::parseArgs($call->args);
        switch (get_class($call->var)){
            case \PhpParser\Node\Expr\Variable::class:
                $that->object = $call->var->name;
                $that->class = $parse->getClassNameByVar($call->var->name);
                break;
            case StaticCall::class:
                $res[] = self::createFromExprStaticCall($call->var, $parse);
                break;
            default:
                break;
        }
        $res[] = $that;
        return $res;

    }

    /**
     * @param $call StaticCall
     * @param $parse IBase
     * @return self[]
     */
    public static function createFromExprStaticCall($call, $parse)
    {

    }

    /**
     * @param $args \PhpParser\Node\Arg[]
     * @return array
     */
    private static function parseArgs($args)
    {
        $calls = [];
        $res = [];
        foreach ($args as $arg)
        {
            $item = new Arg();
            do {
                if($arg->value instanceof Scalar) {
                    $item->type = Arg::SCALAR;
                    $item->name = $arg->value->value;
                    break;
                }

            }while(false);
        }

        return [$calls, $res];
    }
}