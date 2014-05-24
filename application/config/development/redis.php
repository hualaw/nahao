<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//default redis for auto login and verify with ci
$config['redis_db']['session']		= 1;//SESSION
$config['redis_db']['login']	 	= 2;//store phone captcha and email verify code


// Default connection group
$config['redis_default']['host'] = '192.168.11.75';     // IP address or host
$config['redis_default']['port'] = '6379';      // Default Redis port is 6379
$config['redis_default']['password'] = '';      // Can be left empty when the server does not require AUTH
$config['redis_default']['timeout'] = 0.25;

$config['redis_slave']['host'] = '192.168.11.75';
$config['redis_slave']['port'] = '6379';
$config['redis_slave']['password'] = '';       // Can be left empty when the server does not require AUTH
$config['redis_slave']['timeout'] = 0.25;

$config['redis_backup']['host'] = '192.168.11.75';
$config['redis_backup']['port'] = '6379';
$config['redis_backup']['password'] = '';
$config['redis_backup']['timeout'] = 0.25;

/* End of file redis.php */
/* Location: ./application/config/redis.php */
