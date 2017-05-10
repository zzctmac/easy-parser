<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 15:28
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_ as StmtNamespace_;
use st\bean\Namespace_ as NamespaceBean;

class Namespace_ extends Base
{

    /**
     * @var StmtNamespace_
     */
    protected $node;
    protected $class = StmtNamespace_::class;
    /**
     * @return mixed
     */
    public function handle()
    {
        $this->container->addNamespace(NamespaceBean::create($this->node->name->toString()));
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        return [];
    }
}