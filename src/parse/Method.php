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

    /**
     * @var StmtClassMethod
     */
    protected $root;

    protected $visitType;

    /**
     * @var bool
     */
    protected $static;

    public function __construct(StmtClassMethod $root)
    {
        parent::__construct($root);

        $this->visitType = $root->flags;

        $this->init();
    }

    public function init()
    {
        if($this->root->isPublic()) {
            $this->visitType = 1;
        } elseif($this->root->isProtected()) {
            $this->visitType = 2;
        } else {
            $this->visitType = 4;
        }
        $this->static = $this->root->isStatic() ? true : false;

    }



    /**
     * @return mixed
     */
    public function getVisitType()
    {
        return $this->visitType;
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->static;
    }


}