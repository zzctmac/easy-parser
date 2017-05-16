<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-12
 * Time: 14:21
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Arg as NodeArg;
use st\bean\Arg as BeanArg;

class Arg extends Base implements ISonKeys
{
    /**
     * @var NodeArg
     */
    protected $node;
    protected $class = NodeArg::class;

    /**
     * @return mixed
     */
    public function handle()
    {
        $type = null;
        $name = null;
        if($this->node->value instanceof Node\Scalar) {
            $type = BeanArg::SCALAR;
            $name = $this->node->value->value;
        }

        return BeanArg::create($type, $name);
    }

    use SonHelper;

    /**
     * @param NodeArg $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    public function getAllKeys()
    {
        return ['value'];
    }
}