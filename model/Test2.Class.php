<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/29 0029
 * Time: 14:26
 */
class Test2Manager extends TestManager
{
    public function __consturct()
    {
        parent::__construct();
    }

    public function test()
    {
        return parent::get();
    }
    function __destruct()
    {
        echo 'test2 complete';
    }
}