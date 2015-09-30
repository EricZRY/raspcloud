<?php
/**
 * Created by PhpStorm.
 * User: Eric Zhang
 * Date: 2015/9/23 0023
 * Time: 15:52
 */

/**
 * 从各个外部变量中取值
 *
 * @param string $key 外部变量的key
 * @param string $type
 * int,integer -- 取得的变量作为一个int值返回，默认值是0
 * string      -- 取得的变量作为string返回，默认值是NULL。这是默认的返回方式
 * array       -- 取得的变量作为array返回，默认值是一个空的数组
 * bool        -- 取得的变量作为bool值返回，默认值是false
 *
 * @param string $var 代表需要取值的变量类型
 * R  -  $_REQUEST
 * G  -  $_GET
 * P  -  $_POST
 * C  -  $_COOKIE
 * @return mixed 返回key对应的值
 */
function getGPC($key, $type = 'integer', $var = 'R') {
    switch($var) {
        case 'G': $var = &$_GET; break;
        case 'P': $var = &$_POST; break;
        case 'C': $var = &$_COOKIE; break;
        case 'R': $var = &$_REQUEST; break;
    }
    switch($type) {
        case 'int':
        case 'integer':
            $return = isset($var[$key]) ? intval($var[$key]) : 0;
            break;
        case 'string':
            $return = isset($var[$key]) ? $var[$key] : NULL;
            break;
        case 'array':
            $return = isset($var[$key]) ? $var[$key] : array();
            break;
        case 'bool':
            $return = isset($var[$key]) ? (bool)$var[$key] : false;
            break;
        default:
            $return = isset($var[$key]) ? $var[$key] : NULL;
    }
    return $return;
}

/**
 * 获取客户端访问的ip地址，即使用户隐藏在代理的后面也能获取到相应的ip地址
 */
function get_ip() {
    if (_valid_ip($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
        if (_valid_ip(trim($ip))) {
            return $ip;
        }
    }
    if (_valid_ip($_SERVER["HTTP_X_FORWARDED"])) {
        return $_SERVER["HTTP_X_FORWARDED"];
    } elseif (_valid_ip($_SERVER["HTTP_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    } elseif (_valid_ip($_SERVER["HTTP_FORWARDED"])) {
        return $_SERVER["HTTP_FORWARDED"];
    } elseif (_valid_ip($_SERVER["HTTP_X_FORWARDED"])) {
        return $_SERVER["HTTP_X_FORWARDED"];
    } else {
        return $_SERVER["REMOTE_ADDR"];
    }
}
function _valid_ip($ip) {
    if (!empty($ip) && ip2long($ip)!=-1) {
        $reserved_ips = array (
            array('0.0.0.0','2.255.255.255'),
            array('10.0.0.0','10.255.255.255'),
            array('127.0.0.0','127.255.255.255'),
            array('169.254.0.0','169.254.255.255'),
            array('172.16.0.0','172.31.255.255'),
            array('192.0.2.0','192.0.2.255'),
            array('192.168.0.0','192.168.255.255'),
            array('255.255.255.0','255.255.255.255')
        );
        foreach ($reserved_ips as $r) {
            $min = ip2long($r[0]);
            $max = ip2long($r[1]);
            if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
        }
        return true;
    } else {
        return false;
    }
}