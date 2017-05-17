<?php
use PhpParser\ParserFactory;

/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-16
 * Time: 15:54
 */
class FunctionTest extends PHPUnit_Framework_TestCase
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
        $this->nodes = $this->parser->parse(file_get_contents(__DIR__ . '/../function.php'));
        $this->stmtParse = new \st\parse\Stmts($this->nodes);
    }

    public function test_normal()
    {
        $funcParse = new \st\parse\Func($this->nodes[0]->stmts[2]);
        $this->assertEquals('t', $funcParse->getName());
        $fs = $funcParse->getAllUsedFunctions();
        $this->assertEquals(true, $fs[0]->isNew);
        $this->assertEquals('c\\h\\Y', $fs[0]->class->name);
        $this->assertEquals(1, $fs[0]->args[0]->name);
        $ic = $funcParse->getAllImportClasses();
        $this->assertEquals('b\\A', $ic[0]->name);
        $vs = array_values($funcParse->getAllVars());
        $this->assertEquals('y', $vs[0]->name);
        $this->assertEquals(true, $vs[0]->isObject);
        $this->assertEquals('ff', $funcParse->getNameSpace());
    }

    public function test_param()
    {
        $funcParse = new \st\parse\Func($this->nodes[0]->stmts[2]);
        $ps = $funcParse->getParams();
        $this->assertEquals('d', $ps[0]->name);
        $this->assertEquals(\st\bean\Arg::SCALAR, $ps[0]->type);
        $this->assertEquals('c', $ps[1]->name);
        $this->assertEquals(\st\bean\Arg::SCALAR, $ps[1]->type);
        $this->assertEquals('ee', $ps[2]->name);
        $this->assertEquals('c\\h\\Y', $ps[2]->type->name);
    }

}
