<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['template_dir'] = array('default'=>APPPATH.'views','lib'=>APPPATH.'views');
$config['compile_dir'] = APPPATH.'templates_c';
$config['cache_dir'] = APPPATH.'cache';
$config['caching'] = false;
$config['lifetime'] = 60;
$config['debugging'] = false;
$config['compile_check'] = false;
$config['force_compile'] = false;//只改这个就可以 true是不缓存 false是缓存
$config['left_delimiter'] = '{';
$config['right_delimiter'] = '}';

/* End of file smarty.php */
/* Location: ./library/config/smarty.php */     
