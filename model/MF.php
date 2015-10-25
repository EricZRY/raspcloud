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
}