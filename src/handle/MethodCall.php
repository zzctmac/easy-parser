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
use st\bean\Variable;

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
        if(is_object($oN)) {
            $oN = $oN->toString();
        }
        $name = $this->node->name;
        $args = parent::handle();
        if(isset($this->container->getLocalContainer()->variables[$oN])) {
            $object = $this->container->getLocalContainer()->variables[$oN];
            $class = $object->type;
        } else {
            if($oN == 'this') {
                $to = Variable::createThis();
                $this->container->addVariable($to);
                $object = $to;
                $class = Variable::CURRENT_CLASS;
            } else {

                $object = null;
                $class = null;
            }
        }
        $this->container->addCall(Call::createByMethodCall($class, $object, $name, $args));
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        $sons = parent::getSons();
        $sons[] = $this->node->var;
        return $sons;
    }
}