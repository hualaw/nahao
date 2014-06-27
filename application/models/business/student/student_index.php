<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_Index extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_index');
        $this->load->model('business/student/student_course');
        $this->load->model('model/student/model_course');
    }
    
    /**
     * 首页获取不同课程里面的最新轮组成的列表信息
     * @return array $array_return
     */
    public function get_course_latest_round_list()
    {
        #首页获取一门课程里面最新的一轮（在销售中）
        $array_round = $this->model_index->get_course_latest_round();
        $array_return = array();
        if ($array_round)
        {
            #年级
            $array_grade = config_item('grade');
            #首页获取轮的信息列表
            foreach ($array_round as $k=>$v)
            {
                $array_list = $this->get_one_round_info($v['id'],$array_grade);
                #如果这一轮里面老师信息为空，则这个轮不显示在首页上面
                if (empty($array_list['teacher']))
                {
                	continue;
                } else {
                	$array_return[] = $array_list;
                }
            }
        }
        
        return $array_return;
    }
    
    /**
     * 根据round_id获取最新一轮的信息
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_one_round_info($int_round_id,$array_grade)
    {
        $array_return = array();
        $array_return = $this->model_course->get_round_info($int_round_id);
        //var_dump($array_return);die;
        #获取每一轮里面有几次课
        if ($array_return)
        {
            $int_num = $this->model_index->round_has_class_nums($array_return['id']);
            $array_return['class_nums'] = $int_num;
            #获取该轮里面的主讲老师信息
            $teacher = $this->student_course->get_round_team($array_return['id'],TEACH_SPEAKER);
           
            if(isset($teacher['0']) && $teacher['0'])
            {
            	$array_return['teacher'] = $teacher['0'];
            } else {
            	$array_return['teacher'] = array();
            	log_message('ERROR_NAHAO','student_index里面get_one_round_info方法里找不到该老师信息或者用户表里面该老师的status=0，
            	轮id为：'.$array_return['id']);
            }
            
        }

        #适合人群
        $gfrom = $array_return['grade_from'];
        $gto   = $array_return['grade_to'];
        if ($gfrom == $gto)
        {
        	$array_return['for_people'] = $array_grade[$gfrom];
        } else {
        	if (( $gfrom >0  && $gfrom <6) || ($gto >0 && $gto <6 ))
        	{
        		$array_return['for_people'] = $gfrom.'-'.$gto."年级";
        	} else {
        		$array_return['for_people'] = $array_grade[$gfrom].'-'.$array_grade[$gto];
        	}
        }
        #处理数据
        $array_return['start_time'] = date("m月d日",$array_return['start_time']);
        $array_return['end_time'] = date("m月d日",$array_return['end_time']);
        $array_return['img'] = empty($array_return['img']) ? static_url(HOME_IMG_DEFAULT) : get_course_img_by_size($array_return['img'],'general');
        return $array_return;
    }
    
    /**
	 * 申请开课
	 **/
    public function save_apply_teach($param)
    {
//    	var_dump($param);die;
    	foreach ($param as $key => $val){
    		if(!$val && !in_array($key,array('schoolname','school','city'))){
    			echo json_encode(array('status'=>'error','msg'=>$key.'不能为空，请重新填写'));die;
//    			exit('<script>alert("'.$key.'不能为空，请重新填写");history.go(-1);</script>');
    		}
    	}
    	return $this->model_index->save_apply_teach($param);
    }
}
