<?php

/**
 * Created by PhpStorm.
 * User: light
 * Date: 2015/10/25
 * Time: 13:25
 */
class global_configManager extends Manager
{
    public function __construct()
    {
        parent::__construct(MODEL_GLOBAL_CONFIG);
    }

    public function getTest()
    {
        //return phpinfo();
        echo 'start';
        try
        {
            call_user_func('fuck',true);
        }catch (Exception $e)
        {
            echo 'hehe';
        }
    }
}