<?php
/**
 * Created by PhpStorm.
 * User: Eric Zhang
 * Date: 2015/9/23 0023
 * Time: 15:52
 */

/**
 * �Ӹ����ⲿ������ȡֵ
 *
 * @param string $key �ⲿ������key
 * @param string $type
 * int,integer -- ȡ�õı�����Ϊһ��intֵ���أ�Ĭ��ֵ��0
 * string      -- ȡ�õı�����Ϊstring���أ�Ĭ��ֵ��NULL������Ĭ�ϵķ��ط�ʽ
 * array       -- ȡ�õı�����Ϊarray���أ�Ĭ��ֵ��һ���յ�����
 * bool        -- ȡ�õı�����Ϊboolֵ���أ�Ĭ��ֵ��false
 *
 * @param string $var ������Ҫȡֵ�ı�������
 * R  -  $_REQUEST
 * G  -  $_GET
 * P  -  $_POST
 * C  -  $_COOKIE
 * @return mixed ����key��Ӧ��ֵ
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
 * ��ȡ�ͻ��˷��ʵ�ip��ַ����ʹ�û������ڴ���ĺ���Ҳ�ܻ�ȡ����Ӧ��ip��ַ
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