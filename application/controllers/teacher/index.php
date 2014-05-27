<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_Controller {

	public function __construct()
	{
        	parent::__construct();
    }
    
	/**
	 * 老师课程列表
	 */
	public function index()
	{
		$this->smarty->display('teacher/index/today_list.html');
	}
}
