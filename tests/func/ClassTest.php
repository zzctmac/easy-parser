<?php
use PhpParser\ParserFactory;

/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 15:36
 */
class ClassTest extends PHPUnit_Framework_TestCase
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
        $this->nodes = $this->parser->parse(file_get_contents(__DIR__ . '/../c.php'));
        $this->stmtParse = new \st\parse\Stmts($this->nodes);
    }

    public function test_name()
    {
        $cp = new \st\parse\Class_($this->nodes[0]->stmts[1]);
        $this->assertEquals('Info\\Test', $cp->getName());
        $this->assertEquals('co\\Service', $cp->getParentName());
    }

    public function test_attr()
    {
        $cp = new \st\parse\Class_($this->nodes[0]->stmts[1]);
        $attrs = $cp->getAttributes();
        $this->assertEquals('r', $attrs[0]->name);
        $this->assertEquals(1, $attrs[0]->defaultValue['a']);

    }

    public function test_method()
    {
        $cp = new \st\parse\Class_($this->nodes[0]->stmts[1]);
        $ms = $cp->getMethods();
        $this->assertEquals('invoke', $ms['invoke']->getName());
        $fs = $ms['invoke']->getAllUsedFunctions();
        $this->assertEquals(1, $ms['invoke']->getVisitType());
        $this->assertEquals(false, $fs[0]->isNew);
        $this->assertEquals('t', $fs[0]->name);
        $this->assertEquals('c\A', $fs[0]->class->name);
    }

}
