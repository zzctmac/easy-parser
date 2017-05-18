<?php
use PhpParser\ParserFactory;

/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-18
 * Time: 16:01
 */
class Class1Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PhpParser\Parser
     */
    protected $parser;
    /**
     * @var \st\parse\Stmts
     */
    protected $stmtParse;

    protected $nodes;

    protected function setUp()
    {
        \st\handle\GlobalContainer::destroy();
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
        $this->nodes = $this->parser->parse(file_get_contents(__DIR__ . '/../c1.php'));
        $this->stmtParse = new \st\parse\Stmts($this->nodes);
    }

    public function test_name()
    {
        $cp = new \st\parse\Class_($this->nodes[1]);
        $this->assertEquals('C1', $cp->getName());
    }

    public function test_static_method()
    {
        $cp = new \st\parse\Class_($this->nodes[1]);
        $ms = $cp->getMethods();
        $say = $ms['say'];
        $this->assertEquals(1, $say->getVisitType());
        $this->assertEquals(true, $say->isStatic());
    }

    public function test_method()
    {
        $cp = new \st\parse\Class_($this->nodes[1]);
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

}
