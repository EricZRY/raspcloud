<?php
/**
 * Created by PhpStorm.
 * User: Eric Zhang
 * Date: 2015/9/23 0023
 * Time: 15:31
 */
error_reporting(E_ALL & ~E_NOTICE );
define('APP_ROOT', realpath(dirname(__FILE__)));
define('LOG_DIR', APP_ROOT.'/log/');
define('FRAMEWORK', APP_ROOT . '/framework');
define("MODEL", APP_ROOT . '/model');

/** @noinspection PhpIncludeInspection */
include FRAMEWORK . '/common.func.php';
/** @noinspection PhpIncludeInspection */
include APP_ROOT . '/common.func.php';