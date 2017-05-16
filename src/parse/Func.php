<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-8
 * Time: 16:01
 */

namespace st\parse;


use st\bean\ImportClass;
use st\bean\Variable;

use PhpParser\Node\Stmt\Function_ as StmtFunction;

class Func implements IBase,IFunc
{


    /**
     * @var StmtFunction
     */
    protected $root;

    /**
     * @var Stmts
     */
    protected $stmtParse;

    protected $name;

    /**
     * Func constructor.
     * @param $root StmtFunction
     */
    public function __construct($root)
    {
        $this->root = $root;
        $this->stmtParse = new Stmts($root->stmts);
        $this->name = $root->name;
    }


    /**
     * @return null|string
     */
    public function getNameSpace()
    {
        return $this->stmtParse->getNameSpace();
    }

    public function getAllUsedFunctions()
    {
        return $this->stmtParse->getAllUsedFunctions();
    }

    /**
     * @return Variable[]
     */
    public function getAllVars()
    {
        return $this->stmtParse->getAllVars();
    }

    /**
     * @return ImportClass[]
     */
    public function getAllImportClasses()
    {
        return $this->stmtParse->getAllImportClasses();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParams()
    {
        // TODO: Implement getParams() method.
    }
}