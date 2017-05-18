<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 9:34
 */

namespace st\parse;
use PhpParser\Node\Stmt;
use st\bean\ImportClass;
use st\bean\Variable;


abstract class ScopeParse implements IBase
{

    protected $root;

    /**
     * @var Stmts
     */
    protected $stmtParse;



    /**
     * Func constructor.
     * @param $root Stmt
     */
    public function __construct($root)
    {
        $this->root = $root;
        $this->stmtParse = new Stmts($root->stmts);
    }


    /**
     * @return null|string
     */
    public function getNameSpace()
    {
        return $this->stmtParse->getNameSpace();
    }

    /**
     * @return \st\bean\Call[]
     */
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
}