<?php
/**
 * User: zzc
 * Date: 17-5-21
 * Time: 上午8:32
 */

namespace st\parse;


use PhpParser\ParserFactory;
use st\bean\ImportClass;
use st\bean\Variable;
use st\handle\GlobalContainer;

abstract class File implements IBase
{

    /**
     * File constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
        GlobalContainer::destroy();
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
        $nodes = $parser->parse(file_get_contents($file));
        $this->outParse = new Stmts($nodes);
        $realNode = $this->getRealNode($nodes);
        $this->initScopeParse($realNode);

    }

    abstract function getRealNode($nodes);
    abstract function initScopeParse($realNode);

    /**
     * @var ScopeParse
     */
    protected $scopeParse;

    /**
     * @var Stmts
     */
    protected $outParse;

    protected $file;




    /**
     * @return null|string
     */
    public function getNameSpace()
    {
        return $this->scopeParse->getNameSpace();
    }

    public function getAllUsedFunctions()
    {
        return $this->scopeParse->getAllUsedFunctions();
    }

    /**
     * @return Variable[]
     */
    public function getAllVars()
    {
        return $this->scopeParse->getAllVars();
    }

    /**
     * @return ImportClass[]
     */
    public function getAllImportClasses()
    {
        return $this->scopeParse->getAllImportClasses();
    }
}