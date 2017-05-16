<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-8
 * Time: 16:07
 */

namespace st\parse;




use st\bean\Call;
use st\handle\Manager;

class Stmts extends Base
{
    /**
     * @var Manager
     */
    protected $manager;
    public function __construct($nodes)
    {
        parent::__construct($nodes);
        $this->manager = Manager::create();
        $this->manager->handle($this->parserNodes);

    }


    /**
     * @return null|string
     */
    public function getNameSpace()
    {
        $ns =  $this->manager->getContainer()->namespaces;
        if(count($ns) > 0)
            return $ns[0]->name;
        return null;
    }

    /**
     * @return Call[]
     */
    public function getAllUsedFunctions()
    {
        $ns =  $this->manager->getContainer()->calls;
        return $ns;
    }

    public function getAllVars()
    {
        return $this->manager->getContainer()->variables;
    }

    public function getAllImportClasses()
    {
        return $this->manager->getContainer()->importClasses;
    }
}