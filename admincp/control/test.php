<?php

/**
 * Created by PhpStorm.
 * User: Eric Zhang
 * Date: 2015/9/23 0023
 * Time: 16:05
 */
class testcontrol extends adminbase
{
    function __construct()
    {

    }
    public function ontest()
    {
        $mgr = MF::getGlobalConfigManager();
        $ret = $mgr->getTest();

        $mgr2 = MF::getGlobalConfigManager();

        $ret2 = $mgr2->getTest();

        if($ret != $ret2)
        {
            $ret = 'ddddd';
        }
        return $ret;
    }
}