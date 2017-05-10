<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-8
 * Time: 16:07
 */

namespace st\parse;




use st\bean\Namespace_;
use st\handle\Manager;

class Stmts extends Base
{
    /**
     * @var Manager
     */
    protected $manager;
    public function __construct($file, $parser)
    {
        parent::__construct($file, $parser);
        $this->manager = Manager::create();
        $this->manager->handle($this->parserNodes);

    }


    /**
     * @return null|Namespace_
     */
    public function getNameSpace()
    {
        $ns =  $this->manager->getContainer()->namespaces;
        if(count($ns) > 0)
            return $ns[0];
        return null;
    }

    public function getAllUsedFunctions()
    {
        // TODO: Implement getAllUsedFunctions() method.
    }

    public function getAllVars()
    {
        // TODO: Implement getAllVars() method.
    }

    public function getAllImportClasses()
    {
        // TODO: Implement getAllImportClasses() method.
    }

    public function getClassNameByAlias($alias)
    {
        // TODO: Implement getClassNameByAlias() method.
    }

    public function getClassNameByVar($var)
    {
        // TODO: Implement getClassNameByVar() method.
    }
}