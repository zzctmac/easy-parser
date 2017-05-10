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
    protected $file;
    protected $content;
    /**
     * @var Node[]|null
     */
    protected $parserNodes;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * Base constructor.
     * @param $file
     * @param $parser
     */
    public function __construct($file, $parser)
    {
        $this->parser = $parser;
        $this->setFile($file);
    }

    public function setFile($file)
    {
        $this->file = $file;
        $this->content = file_get_contents($file);
        $this->parserNodes = $this->parser->parse($this->content);
    }


}