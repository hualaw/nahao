<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class famous_teacher extends NH_User_Controller {

    function __construct(){
        parent::__construct();
    }
    // 沈明
    function shenming(){
	if( ENVIRONMENT != 'production' )
	{
        	$this->smarty->display('www/famous_teacher/shenming.html');
	}
    }
}
