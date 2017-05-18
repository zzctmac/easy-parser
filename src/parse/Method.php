<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-18
 * Time: 11:11
 */

namespace st\parse;


use PhpParser\Node\Stmt\ClassMethod as StmtClassMethod;

class Method extends Func
{
    protected $visitType;

    public function __construct(StmtClassMethod $root)
    {
        parent::__construct($root);
        $this->visitType = $root->flags;
    }


    /**
     * @return mixed
     */
    public function getVisitType()
    {
        return $this->visitType;
    }

    function hit($node)
    {
        if($node instanceof StmtClassMethod)
            return true;
        return false;
    }


}