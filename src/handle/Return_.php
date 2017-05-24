<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-24
 * Time: 16:54
 */

namespace st\handle;


use PhpParser\Node;

use PhpParser\Node\Stmt\Return_ as StmtReturn;

class Return_ extends Base
{
    /**
     * @var StmtReturn
     */
    protected $node;

    protected $class = StmtReturn::class;

    /**
     * @return mixed
     */
    public function handle()
    {
    }

    /**
     * @return Node[]
     */
    public function getSons()
    {
        $sons = [];
        $sons[] = $this->node->expr;
        return $sons;
    }
}