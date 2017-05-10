<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-8
 * Time: 15:57
 */

namespace st\parse;


use st\bean\ImportClass;
use st\bean\Namespace_;
use st\bean\Variable;

interface IBase
{
    public function setFile($file);

    /**
     * @return Namespace_
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

    /**
     * @param $alias
     * @return ImportClass|null
     */
    public function getClassNameByAlias($alias);
    /**
     * @param $var
     * @return ImportClass|null
     */
    public function getClassNameByVar($var);
}