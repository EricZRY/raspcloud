<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/23 0023
 * Time: 15:26
 */
session_start();
include '../config.inc.php';
include FRAMEWORK. '/mvc/Template.class.php';
include APP_ROOT.'/admincp/control/base.php';
define('ADMIN_CONTROL', APP_ROOT . '/admincp/control');
define('TEMPLATE_DATA_DIR', APP_ROOT . '/cache/templates');
define('TEMPLATE_DIR', APP_ROOT . '/admincp/view');
define('LANGUAGE', APP_ROOT . '/admincp/language');
define('TEMPLATE_EXT', '.htm');

if (is_shutdown()) {
    die('server under maintenance');
}

$ip = get_ip();

$module = getGPC("mod","string");
$action = getGPC('act',"string");
//临时
 if(empty($module) || empty($action))
 {
     $module='test';
     $action='test';
 }
//
$view_datas = array();
$control_file = ADMIN_CONTROL."/{$module}.php";
if (file_exists($control_file)) {
    require $control_file;
} else {
    $view_file = TEMPLATE_DIR.'/'.$module.'/'.$action. TEMPLATE_EXT;
    if(file_exists($view_file)) {
        include renderTemplate($module, $action);
        return;
    }
}

try{
    $classname = $module.'control';
    $control = new $classname();
    $method = 'on'.$action;
    if(method_exists($control, $method) && $action{0} != '_') {
        $data = $control->$method();
    } elseif(method_exists($control, '_call')) {
        $data = $control->_call('on'.$action, '');
    } else {
        exit('Action not found!');
    }

    if(method_exists($control,'on_afterhandler')){
        $m = 'on_afterhandler';
        $control->$m();
    }
    if (isset($data)) {
        //ob_clean();
        header('Content-Type: application/json; charset=UTF-8');
        $ret = json_encode($data);
    }
    else
    {
        header('Content-type: text/html; charset=UTF-8');
    }
}catch (Exception $e){
    $error_msg = $e->__toString();
    $ret = json_encode(array('status'=>'ERROR', 'error_code'=>$e->getCode(), 'error_msg' => $e->getMessage()));
}
$callback = getGPC('callback', 'string');
if (!empty($callback)) $ret = "$callback($ret);";
if (!empty($ret)) die($ret);
if (!empty($view_datas)) {
    extract($view_datas, EXTR_SKIP);
}

@include (renderTemplate($module, $action));