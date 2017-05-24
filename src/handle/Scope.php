<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-24
 * Time: 15:33
 */

namespace st\handle;


use PhpParser\Node;

class Scope extends Base
{

    /**
     * @var Node\Stmt\If_|Node\Stmt\While_|Node\Stmt\Do_
     */
    protected $node;

    public function hit($node)
    {
        static $arr = [
            Node\Stmt\If_::class,
            Node\Stmt\While_::class,
            Node\Stmt\Do_::class
        ];
        $c = get_class($node);
        if(in_array($c, $arr)) {
            $this->node = $node;
            return true;
        }
        return  false;

    }


    /**
     * @return mixed
     */
    public function handle()
    {

    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        return $this->node->stmts;
    }
}