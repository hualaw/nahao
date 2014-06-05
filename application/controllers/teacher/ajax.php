<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller{
 
	/**
	 * 老师端ajax页面数据类
	 */
    public function __construct(){
        parent::__construct();
		$this->load->model('business/teacher/business_teacher','teacher_b');
		$this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
    /**
     * 省市区联动菜单
     * http://teacher.nahaodev.com/ajax/stage_area/13/2
     * param['parentid']
     * param['level']
     */
    public function stage_area(){
    	$parentid = $this->uri->segment(3,0);
    	$level = $this->uri->segment(4,0);
    	if(!$parentid || !$level) exit('参数不全');
    	$area = $this->teacher_b->get_area(array('parentid' => $parentid,'level' => $level));
    	
//    	if(empty($sId)){echo '0';exit;}
//    	$dataArr = $this->tiku->sIdGetKId($sId);
//    	$html = '';
    	$data = array();
//    	if($dataArr){ 
//    		foreach ($dataArr as $key=>$val){
//    			$html .= '<option value="'.$key.'">'.$val.'</option>';
//    		}
//    		$data['html'] = $html;
//    		$data['status'] = 1;
//    	}else{
//    		$data['status'] = 0;
//    	}
    	echo json_encode($data);
    	exit;
    }
    
}