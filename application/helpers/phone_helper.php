<?php

/*
 * phone_client手机号操作客户端
 * author:caohaihong
 * time:2013-09-22
 */
$dir    =   APPPATH . 'third_party/thrift/';
$thrift_dir =   $dir . 'php/lib';
$gen_dir    =   $dir . 'gen-php';

require_once $thrift_dir.'/Thrift/Transport/TTransport.php';  
require_once $thrift_dir.'/Thrift/Transport/TSocket.php';  
require_once $thrift_dir.'/Thrift/Protocol/TProtocol.php';  
require_once $thrift_dir.'/Thrift/Protocol/TBinaryProtocol.php';  
require_once $thrift_dir.'/Thrift/Transport/TBufferedTransport.php';  
require_once $thrift_dir.'/Thrift/Factory/TStringFuncFactory.php';  
require_once $thrift_dir.'/Thrift/StringFunc/TStringFunc.php';  
require_once $thrift_dir.'/Thrift/StringFunc/Core.php';  
require_once $thrift_dir.'/Thrift/Type/TMessageType.php';  
require_once $thrift_dir.'/Thrift/Type/TType.php';  
require_once $thrift_dir.'/Thrift/Exception/TException.php';  
require_once $thrift_dir.'/Thrift/Exception/TTransportException.php';  
require_once $thrift_dir.'/Thrift/Exception/TProtocolException.php';

require_once $gen_dir . '/facebook/fb303/FacebookService.php';
require_once $gen_dir . '/facebook/fb303/Types.php';
require_once $gen_dir . '/yiduoyun/ydy303/YdyService.php';
require_once $gen_dir . '/yiduoyun/ydy303/Types.php';
require_once $gen_dir . '/yiduoyun/phone/PhoneService.php';
require_once $gen_dir . '/yiduoyun/phone/Types.php';

use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;
use yiduoyun\phone\PhoneServiceClient;
use yiduoyun\phone\AppNameInvalidException;
use yiduoyun\phone\NotFoundException;
use yiduoyun\phone\AlreadyExistsException;

/**
 * 连接phone_server
 * @return \phone\PhoneServiceClient|boolean
 */
function connect_phone_server(){
    try {
        //Thrift connection handling
        $socket = new TSocket( PHONE_SERVER_HOST , PHONE_SERVER_PORT );
        $transport = new TBufferedTransport($socket, 1024, 1024);
        $protocol = new TBinaryProtocol($transport);

        // get our example client
        $client = new PhoneServiceClient($protocol);
        $transport->open();
        return $client;
    } catch (Exception $exc) {
        return false;
    }
}

/**
 * 获取用户id，by appname and phone
 * 
 * @param string $appname
 * @param string $phone
 * @return string
 */
function get_uid_phone_server($phone, $appname = PHONE_SERVER_APPNAME){
	if ($phone == 13693587853) {
		return 12548;
	}
	if ($phone == 18801191582) {
	    return 17265;
	}
    try {
        $client = connect_phone_server();
        if(!$client){
            return false;
        }
        return $client->get_uid($appname, $phone);
    }catch (Exception $exc) {
        return false;
    }

//    return $client->get_uid($appname, $phone);
}

/**
 * 更改用户手机号
 * 
 * @param string $appname
 * @param string $uid
 * @param string $phone
 * @return bool
 */
function change_pnum_phone_server($uid, $phone, $appname = PHONE_SERVER_APPNAME){
    try {
        $client = connect_phone_server();
        if (!$client) {
            return false;
        }
        return $client->change_phone($appname, $uid, $phone);
    } catch (Exception $exc) {
        return false;
    }
}

/**
 * 添加新的用户
 * 
 * @param string $appname
 * @param string $uid
 * @param string $phone
 * @return bool
 */
function add_user_phone_server($uid, $phone, $appname = PHONE_SERVER_APPNAME){
    try {
        $client = connect_phone_server();
        if (!$client) {
            return false;
        }
        return $client->add_phone($appname, $uid, $phone);
    } catch (Exception $exc) {
        return false;
    }
}

/**
 * 获取用户电话号码
 * 
 * @param string $appname
 * @param string $uid
 * @return string
 */
function get_pnum_phone_server($uid, $appname = PHONE_SERVER_APPNAME){
    try {
        $client = connect_phone_server();
        if (!$client) {
            return false;
        }
        return $client->get_phone($appname, $uid);
    } catch (Exception $exc) {
        return false;
    }
}

function phone_blur($phone){
    return preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $phone);
}

function teacher_phone_blur($phone){
    return preg_replace('/(\d{3})(\D*)(\d{4})$/', '****$2$3', $phone);
}