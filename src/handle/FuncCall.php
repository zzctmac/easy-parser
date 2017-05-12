<?php
/**
 * User: zzc
 * Date: 17-5-12
 * Time: 下午10:20
 */

namespace st\handle;

use PhpParser\Node\Expr\FuncCall as ExprFuncCall;
use st\bean\Call;

class FuncCall extends WithArg
{
    /**
     * @var ExprFuncCall
     */
    protected $node;
    protected $class = ExprFuncCall::class;

    /**
     * @return mixed
     */
    public function handle()
    {
        $name = $this->node->name->toString();
        $args = parent::handle();
        $this->container->addCall(Call::createByFuncCall($name, $args));
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        return [];
    }
}