<?php

/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-24
 * Time: 10:04
 */

class Doc {
    use \st\common\DocParser;
}

class DocTest extends PHPUnit_Framework_TestCase
{

    public function test_normal()
    {
        $str= <<<EOL
/**
 * Class C1
 * @name  test
 * @ttt hggg
 * @gg
 */
EOL;
        $d = new Doc();
        $doc = $d->parseDoc($str);
        $this->assertEquals('test', $doc['name']);
        $this->assertEquals('hggg', $doc['ttt']);
        $this->assertEquals(1, $doc['gg']);

    }
}
