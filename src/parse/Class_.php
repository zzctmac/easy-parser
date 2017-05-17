<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 15:39
 */

namespace st\parse;

use PhpParser\Node\Stmt\Class_ as StmtClass;

class Class_ extends Hit implements IClass_
{

    /**
     * @var StmtClass
     */
    protected $root;

    public function __construct(StmtClass $root)
    {
        parent::__construct($root);
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

    public function getAttributes()
    {
        // TODO: Implement getAttributes() method.
    }

    public function getMethods()
    {
        // TODO: Implement getMethods() method.
    }
}