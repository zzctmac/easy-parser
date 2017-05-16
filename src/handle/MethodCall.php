<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-12
 * Time: 14:04
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall as ExprMethodCall;
use st\bean\Call;

class MethodCall extends WithArg
{

    /**
     * @var ExprMethodCall
     */
    protected $node;
    protected $class = ExprMethodCall::class;

    /**
     * @return mixed
     */
    public function handle()
    {
        $oN = $this->node->var->name;
        $name = $this->node->name;
        $args = parent::handle();
        if(isset($this->container->variables[$oN])) {
            $object = $this->container->variables[$oN];
            $class = $object->type;
        } else {
            $object = null;
            $class = null;
        }
        $this->container->addCall(Call::createByMethodCall($class, $object, $name, $args));
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        $sons = parent::getSons();
        if($this->node->var instanceof Node\Expr\StaticCall) {
            $sons[] = $this->node->var;
        }
        return $sons;
    }
}