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

    /**
     * @return mixed
     */
    public function handle()
    {
        // TODO: Implement handle() method.
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        // TODO: Implement getSons() method.
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
            $lv = $stmt->left->value;
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
            $rv = $stmt->right->value;
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