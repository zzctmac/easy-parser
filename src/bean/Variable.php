<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-9
 * Time: 14:48
 */

namespace st\bean;


use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\New_;
use st\parse\IBase;

class Variable
{
    public $name;
    public $type;

    /**
     * @param $assign Assign
     * @param $parse IBase
     * @return Variable
     */
    public static function createFromAssign($assign, $parse)
    {
        $that = new self();
        $that->name = $assign->var->name;
        switch (get_class($assign->expr)) {
            case New_::class:
                $parts = $assign->expr->class->parts;
                $that->type = $parse->getClassNameByAlias($parts);
                break;
            default:
                $that->type = null;
        }
        return $that;
    }
}