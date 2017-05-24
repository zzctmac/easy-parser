<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 15:39
 */

namespace st\parse;

use PhpParser\Node\Stmt\Class_ as StmtClass;
use PhpParser\Node\Stmt\ClassMethod;
use st\common\DocParser;
use st\handle\Manager;
use st\handle\Method;

class Class_ extends ScopeParse implements IClass_
{

    /**
     * @var StmtClass
     */
    protected $root;

    public function __construct(StmtClass $root)
    {
        parent::__construct($root);
        $this->init();
    }

    protected $name;
    protected $parentName;
    /**
     * @var \st\parse\Method[]
     */
    protected $methods;

    /**
     * @var string[]
     */
    protected $impls;

    /**
     * @var []
     */
    protected $doc;

    protected function init()
    {
        $container = Manager::create()->getContainer();

        //name
        $ns = $container->namespaces;
        if(!empty($ns)) {
            $this->name =  $container->namespaces[0]->name . "\\" . $this->root->name;
        } else {
            $this->name = $this->root->name;
        }

        //parent name
        if($this->root->extends != null) {
            $pn = $this->root->extends->toString();
            $pn = $container->findClassNameByAlias($pn);
            $this->parentName = $pn;
        } else {
            $this->parentName = null;
        }

        //method
        foreach ($this->root->stmts as $stmt) {
            if($stmt instanceof ClassMethod) {
                $mh = new \st\parse\Method($stmt);
                $this->methods[$mh->getName()] = $mh;
            }
        }

        // implements
        $is = $this->root->implements;
        $this->impls = [];
        foreach ($is as $i) {
            $this->impls[] = $container->findClassNameByAlias($i->toString());
        }

        //doc
        $doc = $this->root->getDocComment();
        if($doc != null)
            $this->doc = $this->parseDoc($doc->getText());
        else
            $this->doc = [];

    }

    use DocParser;


    /**
     * @return []
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * @return \st\bean\Attr[]
     */
    public function getAttributes()
    {
        return $this->stmtParse->getContainer()->attrs;
    }

    /**
     * @return \st\parse\Method[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParentName()
    {
        return $this->parentName;
    }

    public function getImpls()
    {
        return $this->impls;
    }
}