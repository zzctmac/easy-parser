<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-8
 * Time: 15:57
 */

namespace st\parse;


interface IBase
{
    public function setFile($file);
    public function getNameSpace();
    public function getAllUsedFunctions();
    public function getAllVars();
    public function getAllImportClasses();
    public function getClassNameByAlias($alias);
    public function getClassNameByVar($var);
}