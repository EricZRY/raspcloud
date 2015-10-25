<?php

/**
 * Created by PhpStorm.
 * User: light
 * Date: 2015/10/25
 * Time: 12:29
 */
class RcConfig
{
    protected $config_file;
    protected $config = null;

    private function __clone(){}

    protected function __construct($config_file,$options = null)
    {
        $this->config_file = $config_file;
        $this->init($options);
    }

    protected function init($options)
    {
        if(is_array($options) && array_key_exists('process_sections',$options))
        {
            $this->config = parse_ini_file($this->config_file,$options['process_sections']);
        }
        else
        {
            $this->config = parse_ini_file($this->config_file);
        }

        if(!$this->config)
        {
            $this->throwConfigException();
        }
    }

    protected function throwConfigException($msg = 'Config Fault' , $code = 1)
    {
        throw new ConfigException($msg,$code);
    }

    protected static function checkConfigPath($path){
        if (preg_match('/[^a-z0-9\\/\\\\_.: -]/i', $path)) {
            self::throwConfigException('Security check: Illegal character in filename');
        }
        if(!file_exists($path)){
            self::throwConfigException('config path error:' . $path);
        }
    }
}

class ConfigException extends Exception
{

}