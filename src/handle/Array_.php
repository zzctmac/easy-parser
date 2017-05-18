<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-18
 * Time: 10:29
 */

namespace st\handle;


use PhpParser\Node;

use PhpParser\Node\Expr\Array_ as ExprArray;

class Array_ extends Base
{
    /**
     * @var ExprArray
     */
    protected $node;
    protected $class = ExprArray::class;

    /**
     * @return mixed
     */
    public function handle()
    {
        $arr = [];
        foreach ($this->node->items as $item) {
            $k = $item->key->value;
            $v = $item->value;
            if($v instanceof Node\Scalar) {
                $v = $v->value;
            } else {
                $v = null;
            }
            $arr[$k] = $v;
        }
        return $arr;
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        $sons = [];
        foreach ($this->node->items as $item) {
            if($item->value instanceof Node\Scalar) {
                continue;
            }
            $sons[] = $item->value;
        }
        return $sons;
    }
}