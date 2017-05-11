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

    protected function setUp()
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
    }

    public function test_namespace()
    {
        $stmtParse = new \st\parse\Stmts(__DIR__ . '/../normal.php', $this->parser);
        $namespace = $stmtParse->getNameSpace();
        $this->assertEquals('co', $namespace);
    }

    public function test_import_class()
    {
        $stmtParse = new \st\parse\Stmts(__DIR__ . '/../normal.php', $this->parser);
        $classes = $stmtParse->getAllImportClasses();
        $this->assertEquals('A', $classes[0]->alias);
    }

    public function test_vars()
    {
        $stmtParse = new \st\parse\Stmts(__DIR__ . '/../normal.php', $this->parser);
        $vars = $stmtParse->getAllVars();
        $this->assertEquals('c\\h\\Y', $vars['y']->type);
    }

    public function test_call()
    {
        $this->assertEquals(1,2);
        $stmtParse = new \st\parse\Stmts(__DIR__ . '/../normal.php', $this->parser);
        /*$vars = $stmtParse->getAllVars();
        $this->assertEquals('c\\h\\Y', $vars['y']->type);*/
    }
}
