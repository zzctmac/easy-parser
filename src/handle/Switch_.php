<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-24
 * Time: 15:37
 */

namespace st\handle;


use PhpParser\Node;
use PhpParser\Node\Stmt\Switch_ as StmtSwitch;
class Switch_ extends Base
{

    /**
     * @var StmtSwitch
     */
    protected $node;

    protected $class = StmtSwitch::class;

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
        foreach ($this->node->cases as $case) {
            $sons = array_merge($sons, $case->stmts);
        }
        return $sons;
    }
}