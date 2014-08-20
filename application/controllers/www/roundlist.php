<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class Roundlist extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model("business/common/business_list");
    }
    
    /**
     * 课程列表
     * typeId 		=> 		t
	 * qualityId	=>		q
	 * stageId		=> 		st
	 * gradeId		=> 		g
	 * subjectId	=> 		su
	 * order		=>		o
	 * page			=>		p
     */
    function index($queryStr=''){
    	parse_str(str_replace('?','',$queryStr),$param);
    	if(empty($param['typeId'])){exit('类型参数不能为空');}
    	#1. 获取分类结构
//    	$cateList = $this->business_list->getCateList($param);
    	
    	#2. 设置参数初始值
    	$param['sale_status'] = !empty($param['sale_status']) ? $param['sale_status'] : ROUND_SALE_STATUS_SALE;
    	$param['order'] = !empty($param['order']) ? $param['order'] : 1;
    	$param['page'] = !empty($param['page']) ? $param['page'] : 1;
    	$param['num'] = !empty($param['num']) ? $param['num'] : 30;
    	$list = $this->business_list->search($param);
    	$this->smarty->assign('list' , $list);
    	$this->smarty->display('www/student_class/index.html');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */