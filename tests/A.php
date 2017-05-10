<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-4
 * Time: 10:19
 */

namespace co;
use Rpc;
use ro\B;

class A
{
    public function t()
    {
        Rpc::instance(B::t())->g();
    }
}