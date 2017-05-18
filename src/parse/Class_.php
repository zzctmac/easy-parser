<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 15:39
 */

namespace st\parse;

use PhpParser\Node\Stmt\Class_ as StmtClass;
use PhpParser\Node\Stmt\ClassMethod;
use st\handle\Container;
use st\handle\Manager;
use st\handle\Method;

class Class_ extends Hit implements IClass_
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
        $pn =  $this->root->extends->toString();
        $pn = $container->findClassNameByAlias($pn);
        $this->parentName = $pn;

        //method
        foreach ($this->root->stmts as $stmt) {
            if($stmt instanceof ClassMethod) {
                $mh = new \st\parse\Method($stmt);
                $this->methods[$mh->getName()] = $mh;
            }
        }
    }



    function hit($node)
    {
        if($node instanceof StmtClass)
            return true;
        return false;
    }

    public function getDoc()
    {
        // TODO: Implement getDoc() method.
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
        // TODO: Implement getImpls() method.
    }
}