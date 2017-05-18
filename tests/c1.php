<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-18
 * Time: 16:00
 */
use z1\B;
class C1 {
    public static function say()
    {
        echo 123;
    }

    protected function tt()
    {
        $b = new B();
        $b->ss(1,2, new C());
    }

}