<?php
include_once MODEL . '/Manager.Class.php';

/**
 * Created by PhpStorm.
 * User: light
 * Date: 2015/10/25
 * Time: 13:10
 */
class MF
{
    /**
     * @return global_configManager
     */
    public static function getGlobalConfigManager()
    {
        return getManager(MODEL_GLOBAL_CONFIG);
    }

    /**
     * @return TestManager
     */
    public static function getTestManager()
    {
        return getManager('Test');
    }

    /**
     * @return Test2Manager
     */
    public static function getTest2Manager()
    {
        return getManager('Test2');
    }
}