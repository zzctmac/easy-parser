<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 15:39
 */

namespace st\parse;


interface IClass_
{
    public function getDoc();
    public function getAttributes();
    public function getMethods();
    public function getName();
    public function getParentName();
    public function getImpls();
}