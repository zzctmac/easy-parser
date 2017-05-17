<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 15:35
 */

namespace Info;
/**
 * @name 拉黑
 * @service UserSvr.Relation.Test
 * @protocol json
 */
class Test extends co\Service
{
    protected $r = ['a'=>1];
    private $f = 1;
    public $hh = 2;
    public function invoke()
    {
        echo 123;
    }
}