<?php

/*
 * phone_client视屏接口客户端
 * author:caohaihong
 * time:2013-09-22
 */
$dir    =   APPPATH . 'third_party/thrift/';
$thrift_dir =   $dir . 'php/lib';
$gen_dir    =   $dir . 'video-gen-php';

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

require_once $gen_dir . '/ServerManage.php';return;
require_once $gen_dir . '/Types.php';

use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;

function connect_video_server(){
    try {
        //Thrift connection handling
        $socket = new TSocket( VIDEO_SERVER_HOST , VIDEO_SERVER_PORT );
        $transport = new TBufferedTransport($socket, 1024, 1024);
        $protocol = new TBinaryProtocol($transport);

        // get our example client
        $client = new ServerManageClient($protocol);
//        var_dump($transport->open());
        $transport->open();
        return $client;
    } catch (Exception $exc) {
        echo $exc->getMessage();
        return false;
    }
    
}

/**
 * 视屏服务器文档重置
 * @param type $classId
 * @return boolean
 */
function reload_class_docs_video_server($classId){
    try {
        $client = connect_video_server();
        if ($client) {
            return $client->ReloadClassDocs($classId);
        } else {
            return false;
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
//        echo $exc->getTraceAsString();
    }

    
}
