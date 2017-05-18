<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-18
 * Time: 10:15
 */

namespace st\handle;


use PhpParser\Node;

use PhpParser\Node\Stmt\Property;
use st\bean\Variable;

class Attr extends Base
{

    /**
     * @var Property
     */
    protected $node;
    protected $class = Property::class;

    /**
     */
    public function handle()
    {
        $property = $this->node->props[0];

        $name = $property->name;
        $vt = $this->node->flags;
        $ah = new Array_(Manager::create()->getContainer());
        if($property->default instanceof Node\Expr\Array_) {
            $ah->hit($property->default);
            $defaultValue = $ah->handle();
        } elseif($property->default instanceof Node\Scalar) {
            $defaultValue = $property->default->value;
        } else {
            $defaultValue = null;
        }
        $type = Variable::getTypeByScalar($property->default);
        $o = \st\bean\Attr::create($name, $type, false, $vt, $defaultValue);
        $this->container->addAttr($o);
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        return [];
    }
}