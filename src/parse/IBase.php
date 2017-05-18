<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-8
 * Time: 15:57
 */

namespace st\parse;


use st\bean\ImportClass;
use st\bean\Variable;

interface IBase
{

    /**
     * @return null|string
     */
    public function getNameSpace();
    public function getAllUsedFunctions();

    /**
     * @return Variable[]
     */
    public function getAllVars();

    /**
     * @return ImportClass[]
     */
    public function getAllImportClasses();

}