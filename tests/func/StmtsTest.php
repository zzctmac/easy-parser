<?php
use PhpParser\ParserFactory;

/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-9
 * Time: 10:55
 */
class StmtsTest extends PHPUnit_Framework_TestCase
{

    protected $parser;
    /**
     * @var \st\parse\Stmts
     */
    protected $stmtParse;

    protected function setUp()
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
        $this->stmtParse = new \st\parse\Stmts(__DIR__ . '/../normal.php', $this->parser);
    }

    public function test_namespace()
    {
        $namespace = $this->stmtParse->getNameSpace();
        $this->assertEquals('co', $namespace);
    }

    public function test_import_class()
    {
        $classes = $this->stmtParse->getAllImportClasses();
        $this->assertEquals('A', $classes[0]->alias);
    }

    public function test_vars()
    {
        $vars = $this->stmtParse->getAllVars();
        $this->assertEquals('c\\h\\Y', $vars['y']->type);
    }

    public function test_call_0()
    {
        $fs = $this->stmtParse->getAllUsedFunctions();
        $this->assertEquals(true, $fs[0]->isNew);
        $this->assertEquals('c\\h\\Y', $fs[0]->class->name);
        $this->assertEquals(1, $fs[0]->args[0]->name);
        $this->assertEquals(true, $fs[1]->isMethod);
        $this->assertEquals('zzc', $fs[1]->args[0]->name);
        $this->assertEquals('y', $fs[1]->object->name);
        $this->assertEquals('c\\h\\Y', $fs[1]->object->type);
        $this->assertEquals(2, $fs[1]->args[1]->name);
        $this->assertEquals('g\\ed', $fs[2]->name);
        $this->assertEquals(1, $fs[4]->isStatic);
    }

    public function test_call_1()
    {
        $fs = $this->stmtParse->getAllUsedFunctions();
        $this->assertEquals(true, $fs[5]->isStatic);
        $this->assertEquals(true, $fs[6]->isMethod);
        $this->assertEquals('d', $fs[7]->name);
        $this->assertEquals('tt', $fs[8]->name);
        $this->assertEquals(true, $fs[8]->isStatic);
    }
}
