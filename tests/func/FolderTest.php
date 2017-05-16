<?php
use PhpParser\ParserFactory;

/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-16
 * Time: 10:52
 */
class FolderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PhpParser\Parser
     */
    protected $parser;


    protected function setUp()
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
    }
    public function test_add()
    {
        $code = '<?php  1 + 2; ?>';
        $nodes = $this->parser->parse($code);
        $op = $nodes[0];
        $arg = \st\handle\BinaryOp::tryFolder($op);
        $this->assertEquals(3, $arg->name);
    }

    public function test_param_3()
    {
        $code = '<?php  1 + 2 + 3; ?>';
        $nodes = $this->parser->parse($code);
        $op = $nodes[0];
        $arg = \st\handle\BinaryOp::tryFolder($op);
        $this->assertEquals(6, $arg->name);
    }

    public function test_concat()
    {
        $code = '<?php  "1" . 2; ?>';
        $nodes = $this->parser->parse($code);
        $op = $nodes[0];
        $arg = \st\handle\BinaryOp::tryFolder($op);
        $this->assertEquals("12", $arg->name);
    }

    public function test_null()
    {
        $code = '<?php  $a->b() . 2; ?>';
        $nodes = $this->parser->parse($code);
        $op = $nodes[0];
        $arg = \st\handle\BinaryOp::tryFolder($op);
        $this->assertEquals(null, $arg);
    }

    public function test_nature()
    {
        $code = '<?php  1 + 2 - 3 . "zzc"; ?>';
        $nodes = $this->parser->parse($code);
        $op = $nodes[0];
        $arg = \st\handle\BinaryOp::tryFolder($op);
        $this->assertEquals("0zzc", $arg->name);
    }


}
