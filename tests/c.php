<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-17
 * Time: 15:35
 */

namespace Info;
use co\Service;
/**
 * @name 拉黑
 * @service UserSvr.Relation.Test
 * @protocol json
 */
class Test extends Service
{
    protected $r = ['a'=>1];
    private $f = 1;
    private $fff;
    public $hh;
    public function invoke()
    {
        c\A::t();
        echo 123;
    }
}