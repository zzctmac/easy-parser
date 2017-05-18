<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-15
 * Time: 14:13
 */

namespace st\handle;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall as ExprStaticCall;
use st\bean\Call;
use st\bean\ImportClass;

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
        $cn = $this->node->class->toString();
       $class = $this->container->findClassByAlias($cn);
       if($class == null) {
           $class = ImportClass::create($cn, $cn);
           $this->container->addImportClasses($class);
       }
       $name = $this->node->name;
       $args = parent::handle();
       $this->container->addCall(Call::createByStaticCall($class, $name, $args));
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