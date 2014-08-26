<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//default redis for auto login and verify with ci
$config['redis_db']['session'] = 1; //SESSION
$config['redis_db']['login'] = 2; //store phone captcha and email verify code
$config['redis_db']['order'] = 3; //订单
$config['redis_db']['captcha'] = 4; //验证码发送频率
$config['redis_db']['live_class_token'] 		= 5; //进入直播课的token
$config['redis_db']['recent_view_data'] 		= 6; //最近浏览

// Default connection group
$config['redis_default']['host'] = '10.132.80.157'; // IP address or host
$config['redis_default']['port'] = '6379'; // Default Redis port is 6379
$config['redis_default']['password'] = ''; // Can be left empty when the server does not require AUTH
$config['redis_default']['timeout'] = 0.25;

$config['redis_slave']['host'] = '10.132.80.157';
$config['redis_slave']['port'] = '6379';
$config['redis_slave']['password'] = ''; // Can be left empty when the server does not require AUTH
$config['redis_slave']['timeout'] = 0.25;

$config['redis_backup']['host'] = '10.132.80.157';
$config['redis_backup']['port'] = '6379';
$config['redis_backup']['password'] = '';
$config['redis_backup']['timeout'] = 0.25;

/* End of file redis.php */
/* Location: ./application/config/redis.php */
