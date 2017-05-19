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
use st\bean\ImportClass;

class New_ extends WithArg
{

    /**
     * @var ExprNew
     */
    protected $node;
    protected $class = ExprNew::class;

    /**
     */
    public function handle()
    {
        $cn = $this->node->class->toString();
        $class = $this->container->findClassByAlias($cn);
        if($class == null) {
            $class = ImportClass::create($cn, $cn);
        }
        $call = Call::createByNew($class, parent::handle());
        $this->container->addCall($call);
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        $sons = parent::getSons();
        return $sons;
    }
}