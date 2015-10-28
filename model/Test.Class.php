<?php

/**
 * Created by PhpStorm.
 * User: light
 * Date: 2015/10/28
 * Time: 22:49
 */
class  TestManager
{
    private  $onrow;
    private  $file_name;
    public static $count = 0 ;

    public function __construct()
    {
        if(self::$count > 0) throw new Exception("Wrong!!!",ERROR_CRASH);
        $this->file_name = APP_ROOT.'/model/test2.json';
        $this->onrow = json_decode(file_get_contents($this->file_name),true);
        self::$count = self::$count + 1;
    }

    public function get()
    {
        return $this->onrow;
    }

    public function set($data)
    {
        $this->onrow = $data;
    }

    function __destruct()
    {
        echo 'sfadsf'.self::$count = 99;
        $ret = file_put_contents($this->file_name,json_encode($this->onrow));
        if($ret == false) throw new Exception("Wrong!",ERROR_CRASH);
    }
}