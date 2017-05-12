<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 15:24
 */

namespace st\handle;


use PhpParser\Node;

abstract class Base implements IBase
{
    /**
     * @var Node
     */
    protected $node;
    protected $class;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Base constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function hit($node)
    {
        if(get_class($node) === $this->class) {
            $this->node = $node;
            return true;
        }
        return false;
    }


}