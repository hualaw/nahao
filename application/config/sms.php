<?php
$config['smsversion'] = 3; // 1:CMCC.  2:MD. 3：大汉三通

switch($config['smsversion']){
  case 1:
        //$config['api_uri'] = 'http://192.168.11.12:8080/sms/SendSms';
        $config['api_uri'] = 'http://10.160.29.246:8080/sms/SendSms';
        $config['server_ip'] = '192.168.11.113';
        $config['secret'] = 'd41d8cd98f00b204e9800998ecf8427e';
        $config['sign'] = '';
        $config['subcode'] = '';
        break;
  case 2:
        $config['api_uri'] = '';
        $config['server_ip'] = '';
        $config['sn'] = 'SDK-BBX-010-18603';
        $config['secret'] = "MD45&(17";
        $config['sign'] = '【那好网】';
        $config['subcode'] = '';
        break;
    case 3://大汉三通
        $config['api_uri'] = 'http://3tong.net/http/sms/Submit';
        $config['server_ip'] = '';
        $config['sn'] = 'dh20994';  //sms_account
        $config['secret'] = 'tizi2014'; //sms_pwd
        $config['sign'] = '【那好网】';
        //$config['sign'] = '';
        $config['subcode'] = '01';
        break;
}
	// $config['api_uri'] = 'http://192.168.11.12:8080/sms/SendSms';
	// $config['server_ip'] = '192.168.11.113';
	// $config['secret'] = 'd41d8cd98f00b204e9800998ecf8427e';

/* End of file sms.php */
/* Location: ./application/config/sms.php */  
