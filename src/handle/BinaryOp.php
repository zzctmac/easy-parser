<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-16
 * Time: 10:47
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp as ExprBinaryOp;
class BinaryOp extends Base
{

    /**
     * @var ExprBinaryOp
     */
    protected $node;
    protected $class = ExprBinaryOp::class;

    public function hit($node)
    {
        $res = $node instanceof ExprBinaryOp;
        if($res){
            $this->node = $node;
        }
        return $res;
    }


    /**
     * @return mixed
     */
    public function handle()
    {

        /*if(self::tryFolder($this->node))
            return;*/


    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        $sons = [];
        if(self::tryFolder($this->node))
            return $sons;
        if(!$this->node->left instanceof ExprBinaryOp)
            $sons[] = $this->node->left;
        if(!$this->node->right instanceof ExprBinaryOp)
            $sons[] = $this->node->right;
        return $sons;
    }

    /**
     * @param $stmt ExprBinaryOp
     * @return \st\bean\Arg|null
     */
    public static function tryFolder($stmt)
    {
        // 递归调用
        if($stmt->left instanceof ExprBinaryOp) {
            $lArg = self::tryFolder($stmt->left);
            if($lArg === null)
                return null;
            $lv = $lArg->name;
        }else if($stmt->left instanceof Node\Scalar)
        {
            $lv = $stmt->left->value ?? null;
        } else {
            return null;
        }

        if($stmt->right instanceof ExprBinaryOp) {
            $rArg = self::tryFolder($stmt->right);
            if($rArg === null)
                return null;
            $rv = $rArg->name;
        }else if($stmt->right instanceof Node\Scalar)
        {
            $rv = $stmt->right->value ?? null;
        } else {
            return null;
        }

        $type = \st\bean\Arg::SCALAR;
        $name = null;
        switch (get_class($stmt)) {
            case ExprBinaryOp\Plus::class:
                $name = $lv + $rv;
                break;
            case ExprBinaryOp\Div::class:
                $name = $lv / $rv;
                break;
            case ExprBinaryOp\Mul::class:
                $name = $lv * $rv;
                break;
            case ExprBinaryOp\Minus::class:
                $name = $lv - $rv;
                break;
            case ExprBinaryOp\Concat::class:
                $name = $lv . $rv;
                break;
            default:
                break;
        }
        return \st\bean\Arg::create($type, $name);
    }
}