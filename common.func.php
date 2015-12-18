<?php
/**
 * Created by PhpStorm.
 * User: Eric Zhang
 * Date: 2015/9/23 0023
 * Time: 15:31
 */

/**
 * 检测服务器是否关闭
 * @param null $app_config
 * @return bool
 */
function is_shutdown($app_config = null)
{
    if(defined('SERVER_SHUTDOWN') && SERVER_SHUTDOWN == true)
    {
        return true;
    }
    else return false;
}

function getManager($model,$reload = false)
{
    if(empty($_ENV['MODEL'][$model]) || $reload)
    {
        $manager_flie_name = MODEL.'/'.$model.'.Class.php';
        $manager_class_name = $model.'Manager';
        if(file_exists($manager_flie_name))
        {
            include_once $manager_flie_name;
            $_ENV['MODEL'][$model] = new $manager_class_name();
        }
        else
        {
            $_ENV['MODEL'][$model]  = new Manager($model);
        }
    }
    return $_ENV['MODEL'][$model];
}
// 2015/12/08/15:13