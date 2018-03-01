<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 9:44
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Param as NodeParam;

class Param extends Base
{
    /**
     * @var NodeParam
     */
    protected $node;
    protected $class = NodeParam::class;

    /**
     * @return mixed
     */
    public function handle()
    {
        $name = $this->node->name;
        $type = \st\bean\Arg::SCALAR;
        if(is_object($this->node->type))
            $type = $this->container->findClassByAlias($this->node->type->toString());
        return \st\bean\Arg::create($type, $name);
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        return [];
    }
}