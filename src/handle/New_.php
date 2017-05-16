<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-12
 * Time: 11:19
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Expr\New_ as ExprNew;
use st\bean\Call;

class New_ extends WithArg
{

    /**
     * @var ExprNew
     */
    protected $node;
    protected $class = ExprNew::class;

    /**
     * @return mixed
     */
    public function handle()
    {
        $call = Call::createByNew($this->container->findClassByAlias($this->node->class->toString()), parent::handle());
        $this->container->addCall($call);
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        //todo: sons
        return [];
    }
}