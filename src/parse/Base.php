<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-8
 * Time: 16:01
 */

namespace st\parse;


use PhpParser\Node;
use PhpParser\Parser;

abstract class Base implements IBase
{
    /**
     * @var Node[]|null
     */
    protected $parserNodes;

    /**
     * Base constructor.
     * @param $nodes
     */
    public function __construct($nodes)
    {

        $this->parserNodes = $nodes;
    }


}