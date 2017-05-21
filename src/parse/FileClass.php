<?php
/**
 * User: zzc
 * Date: 17-5-21
 * Time: 上午8:41
 */

namespace st\parse;


use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Class_ as StmtClass;

class FileClass extends File implements IClass_
{

    /**
     * @var Class_
     */
    protected $scopeParse;

    function getRealNode($nodes)
    {
        if(count($nodes) == 1 && $nodes[0] instanceof Namespace_) {
            return $this->getRealNode($nodes[0]->stmts);
        }  else {
            foreach ($nodes as $node) {
                if($node instanceof StmtClass)
                    return $node;
            }
        }
        return null;
    }

    function initScopeParse($realNode)
    {
        $this->scopeParse = new Class_($realNode);
    }

    public function getDoc()
    {
        return $this->scopeParse->getDoc();
    }

    /**
     * @return \st\bean\Attr[]
     */
    public function getAttributes()
    {
        return $this->scopeParse->getAttributes();
    }

    /**
     * @return Method[]
     */
    public function getMethods()
    {
        return $this->scopeParse->getMethods();
    }

    public function getName()
    {
        return $this->scopeParse->getName();
    }

    public function getParentName()
    {
        return $this->scopeParse->getParentName();
    }

    public function getImpls()
    {
        return $this->scopeParse->getImpls();
    }

}