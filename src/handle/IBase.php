<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 15:22
 */

namespace st\handle;


use PhpParser\Node;

interface IBase
{
    /**
     * @param $node Node
     * @return mixed
     */
    public function hit($node);

    /**
     * @return mixed
     */
    public function handle();

    /**
     * @return Node[]
     */
    public function getSons();
}