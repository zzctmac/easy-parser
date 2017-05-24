<?php

/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-24
 * Time: 15:16
 */
class UnBlockClassTest extends PHPUnit_Framework_TestCase
{

    public function test_normal()
    {
        $cp = new \st\parse\FileClass(__DIR__ . '/../UnBlock.php');
        $cn = $cp->getName();
        $this->assertEquals('UserSvr\\Service\\Relation\\UnBlock', $cn);
        $this->assertEquals('Seeker\\Message\\Service', $cp->getParentName());
        $this->assertEquals(0, count($cp->getImpls()));
        $doc = $cp->getDoc();
        $this->assertEquals('取消拉黑', $doc['name']);
        $this->assertEquals('UserSvr.Relation.UnBlock', $doc['service']);
        $this->assertEquals('json', $doc['protocol']);
        $ms = $cp->getMethods();
        $invoke = $ms['invoke'];
        $this->assertEquals('invoke', $invoke->getName());
        $ifs = $invoke->getAllUsedFunctions();
        $this->assertEquals('getData', $ifs[0]->name);
    }
}
