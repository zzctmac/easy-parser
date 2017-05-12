<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-12
 * Time: 14:30
 */

namespace st\handle;


use PhpParser\Node;

abstract class WithArg extends Base
{

    /**
     * @var Arg
     */
    protected $argHandler;
    protected $nodeArgs;
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->argHandler = new Arg($container);
    }

    /**
     */
    function setNodeArgs()
    {
        $this->nodeArgs = $this->node->args;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $this->setNodeArgs();
        $argBeans = [];
        foreach ($this->nodeArgs as $arg) {
            $this->argHandler->setNode($arg);
            $argBeans[] = $this->argHandler->handle();
        }
        return $argBeans;
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        // TODO: Implement getSons() method.
    }
}