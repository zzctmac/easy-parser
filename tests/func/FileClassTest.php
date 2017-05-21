<?php

/**
 * User: zzc
 * Date: 17-5-21
 * Time: 上午8:47
 */
class FileClassTest extends PHPUnit_Framework_TestCase
{

    public function test_c()
    {
        $cp = new \st\parse\FileClass(__DIR__ . '/../c.php');
        $this->assertEquals('Info\\Test', $cp->getName());
        $this->assertEquals('co\\Service', $cp->getParentName());
        $attrs = $cp->getAttributes();
        $this->assertEquals('r', $attrs[0]->name);
        $this->assertEquals(1, $attrs[0]->defaultValue['a']);
        $ms = $cp->getMethods();
        $this->assertEquals('Info\\Test', $cp->getName());
        $this->assertEquals('co\\Service', $cp->getParentName());
        $this->assertEquals('invoke', $ms['invoke']->getName());
        $fs = $ms['invoke']->getAllUsedFunctions();
        $this->assertEquals(1, $ms['invoke']->getVisitType());
        $this->assertEquals(false, $fs[0]->isNew);
        $this->assertEquals('t', $fs[0]->name);
        $this->assertEquals('c\A', $fs[0]->class->name);
    }

    public function test_c1()
    {
        $cp = new \st\parse\FileClass(__DIR__ . '/../c1.php');
        $this->assertEquals('C1', $cp->getName());
        $ms = $cp->getMethods();
        $say = $ms['say'];
        $this->assertEquals(1, $say->getVisitType());
        $this->assertEquals(true, $say->isStatic());
        $ms = $cp->getMethods();
        $tt = $ms['tt'];
        $this->assertEquals(2, $tt->getVisitType());
        $this->assertEquals(false, $tt->isStatic());
        $fs = $tt->getAllUsedFunctions();
        $this->assertEquals(3, count($fs));
        $f = $fs[0];
        $this->assertEquals(true, $f->isNew);
        $f = $fs[1];
        $this->assertEquals('ss', $f->name);
        $this->assertEquals('b', $f->object->name);
        $this->assertEquals('z1\\B', $f->object->type);

        $f = $fs[2];
        $this->assertEquals(true, $f->isNew);
    }

    public function test_c3()
    {
        $cp = new \st\parse\FileClass(__DIR__ . '/../c3.php');
        $this->assertEquals('A', $cp->getName());
        $attrs = $cp->getAttributes();
        $this->assertEquals(1, $attrs[0]->defaultValue);
        $this->assertEquals('bb', $attrs[0]->name);
        $this->assertEquals(1, count($attrs));
    }


}
