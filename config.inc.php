<?php
/**
 * Created by PhpStorm.
 * User: Eric Zhang
 * Date: 2015/9/23 0023
 * Time: 15:31
 */
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL & ~E_NOTICE );
define('APP_ROOT', realpath(dirname(__FILE__)));
define('LOG_DIR', APP_ROOT.'/log/');
define('FRAMEWORK', APP_ROOT . '/framework');
define("MODEL", APP_ROOT . '/model');

///lgn; angpnrgiunnnf paiioja []

/** @noinspection PhpIncludeInspection */
include APP_ROOT . '/constants/constants.model.php';

/** @noinspection PhpIncludeInspection */
include FRAMEWORK . '/common.func.php';
/** @noinspection PhpIncludeInspection */
include APP_ROOT . '/common.func.php';
/** @noinspection PhpIncludeInspection */
include APP_ROOT .'/model/MF.php';

//oasdf;o onoi[ionoioi oihoekfpmpmffmfmfmasdfpoasjfp]