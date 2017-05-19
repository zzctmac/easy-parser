<?php
use PhpParser\ParserFactory;

/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-19
 * Time: 13:58
 */
class ArrayTest extends PHPUnit_Framework_TestCase
{

    public function test_normal()
    {
        \st\handle\GlobalContainer::destroy();
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
        $nodes = $parser->parse(file_get_contents(__DIR__ . '/../ar.php'));
        $stmtParse = new \st\parse\Stmts($nodes);
        $fs = $stmtParse->getAllUsedFunctions();
        $this->assertEquals(2, count($fs));
        $f = $fs[0];
        $this->assertEquals(1, $f->args[0]->name['a']);
        $this->assertEquals(null, $f->args[0]->name['d']);
    }
}
