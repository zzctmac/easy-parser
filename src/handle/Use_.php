<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 16:41
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Stmt\Use_ as StmtUse_;
use st\bean\ImportClass;

class Use_ extends Base
{

    /**
     * @var StmtUse_
     */
    protected $node;
    protected $class = StmtUse_::class;

    /**
     * @return mixed
     */
    public function handle()
    {
        if($this->node->type != 1)
            return ;
        foreach ($this->node->uses as $useUse) {
            $this->container->addImportClasses(ImportClass::create($useUse->name->toString(), $useUse->alias));
        }
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        return [];
    }
}