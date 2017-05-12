<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 18:00
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Expr\Assign as ExprAssign;
use st\bean\Variable;

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
        $type = null;
        $isObject = false;
        if($this->node->expr instanceof Node\Expr\New_) {
            $type = $this->node->expr->class->toString();
            $isObject = true;
        } else if($this->node->expr instanceof Node\Scalar){
            switch (get_class($this->node->expr)) {
                case Node\Scalar\LNumber::class:
                case Node\Scalar\DNumber::class:
                    $type = 'number';
                    break;
                case Node\Scalar\String_::class:
                    $type = 'string';
                    break;
                default:
                    break;
            }
        }
        $this->container->addVariable(Variable::create($name, $type, $isObject));
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        return [$this->node->expr];
    }
}