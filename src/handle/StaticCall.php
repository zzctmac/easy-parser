<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-15
 * Time: 14:13
 */

namespace st\handle;

use PhpParser\Node\Expr\StaticCall as ExprStaticCall;
use st\bean\Call;

class StaticCall extends WithArg
{
    /**
     * @var ExprStaticCall
     */
    protected $node;
    protected $class = ExprStaticCall::class;

    /**
     * @return mixed
     */
    public function handle()
    {
       $class = $this->container->findClassByAlias($this->node->class->toString());
       $name = $this->node->name;
       $args = parent::handle();
       $this->container->addCall(Call::createByStaticCall($class, $name, $args));
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        return [];
    }

}