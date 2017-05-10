<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 18:00
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Expr\Assign as ExprAssign;

class Assign extends Base
{

    /**
     * @var ExprAssign
     */
    protected $node;
    protected $class = ExprAssign::class;

    /**
     * @return mixed
     */
    public function handle()
    {
        $name = $this->node->var->name;
        if($this->node->expr instanceof Node\Expr\New_) {
            $this->node->expr->class->toString();
        }
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        // TODO: Implement getSons() method.
    }
}