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

    public function test_normal()
    {
        $cp = new \st\parse\Class_($this->nodes[0]->stmts[0]);
    }
}
