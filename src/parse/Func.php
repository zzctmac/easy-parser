<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-8
 * Time: 16:01
 */

namespace st\parse;



use PhpParser\Node\Stmt\Function_ as StmtFunction;
use st\bean\Arg;
use st\handle\Manager;
use st\handle\Param;

class Func extends Hit implements IFunc
{
    protected $name;

    /**
     * @var StmtFunction
     */
    protected $root;

    public function __construct(StmtFunction $root)
    {
        parent::__construct($root);
        $this->name = $root->name;
        $this->initArgs();
    }

    /**
     * @var Arg[]
     */
    protected $params;

    protected function initArgs()
    {
        $handler = new Param(Manager::create()->getContainer());
        $params = $this->root->getParams();
        foreach ($params as $param) {
            $handler->hit($param);
            $this->params[] = $handler->handle();
        }
    }


    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Arg[]
     */
    public function getParams()
    {
        return $this->params;
    }

    function hit($node)
    {
        if($node instanceof StmtFunction)
            return true;
        return false;
    }
}