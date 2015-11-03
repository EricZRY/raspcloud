<?php
/**
 * Created by PhpStorm.
 * User: light
 * Date: 2015/10/28
 * Time: 23:01
 */
include '../config.inc.php';
include APP_ROOT.'/model/Test.Class.php';
$file = APP_ROOT.'/model/test2.json';
$a = array(
    'yy'=>array('cccc'=>1,45,"sdfsf"),
    array("sdfsa",67,"ddd"=>0),
    46,
    "sdfasgfxx"
);
//file_put_contents($file,json_encode($a));
//$test_mgr = MF::getTestManager();
//$data = $test_mgr->get();
//$data = array("a"=>$a,time());
//$test_mgr->set($data);
//echo 'count'.TestManager::$count;
//$new_mgr = MF::getTestManager();
//$new_mgr->set($data);
//$data2 = $test_mgr->get();
//if($data2 != $data) echo 'hehe';
//echo 'count2'.TestManager::$count;
//$mgr = MF::getTest2Manager();
//$ret = $mgr->test();
//$ret = null;
//$mgr = null;
MF::getGlobalConfigManager()->getTest();

