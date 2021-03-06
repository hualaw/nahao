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
        $array_round = $this->model_index->get_course_new_round(0,3);
//        echo $this->db->last_query();
        $array_return = array();
        if ($array_round)
        {
            #年级
            $array_grade = config_item('grade');
            #首页获取轮的信息列表
            foreach ($array_round as $key=>$value)
            {
                $array_list = $this->get_one_round_info($value['id'],$array_grade);
                #如果这一轮里面老师信息为空，则这个轮不显示在首页上面
                if (empty($array_list['teacher']))
                {
                	continue;
                } else {
                	$array_return[] = $array_list;
                }
            }
        }

//        exit;
        return $array_return;
    }

    /*
     *
     *
     */
    public function get_course_hot()
    {
        #首页获取一门课程里面最新的一轮（在销售中）
        $array_round = $this->model_index->get_course_hot_round(0,3);
        $array_return = array();
        if ($array_round)
        {
            #年级
            $array_grade = config_item('grade');
            #首页获取轮的信息列表
            foreach ($array_round as $key=>$value)
            {
                $array_list = $this->get_one_round_info($value['id'],$array_grade);
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
        $array_return['start_time'] = date("n月j日",$array_return['start_time']);
        $array_return['end_time'] = date("n月j日",$array_return['end_time']);
        $array_return['img'] = $array_return['img'];
        }
        return $array_return;
    }
    
    /**
	 * 申请开课
	 **/
    public function save_apply_teach($param)
    {
//    	var_dump($param);die;
		$Chinese = array(
			'course' => '课程名称','resume' => '个人简介','subject' => '试讲科目','province' => '省份',
			'area' => '区','school_type' => '学校类型','teach_years' => '实际教龄',
			'course_intro' => '课程介绍','teach_type' => '讲课方式','title' => '教师职称',
			'age' => '年龄','phone' => '手机号码','email' => '常用邮箱','qq' => 'QQ号',
			'start_time' => '试讲开始时间','end_time' => '试讲结束时间','name' => '真实姓名',
		);
    	foreach ($param as $key => $val){
    		if(!$val && !in_array($key,array('schoolname','school','city'))){
    			echo json_encode(array('status'=>'error','msg'=>'【'.$Chinese[$key].'】不能为空，请重新填写'));die;
//    			exit('<script>alert("'.$key.'不能为空，请重新填写");history.go(-1);</script>');
    		}
    		if($key=='name' && !check_name_length($val)){
    			echo json_encode(array('status'=>'error','msg'=>'真实姓名要控制在4~25个字符,一个汉字按两个字符计算'));die;
    		}
    	}
    	return $this->model_index->save_apply_teach($param);
    }
    
    /**
     * 没有加nh_dbug参数 过滤掉测试轮
     * @param  $array_data
     * @return $array_data
     */
    public function filter_test_round($array_data)
    {
    	foreach ($array_data as $k=>$v)
		{
			if($v['is_test'] == '1')
			{
				unset($array_data[$k]);
			}
		}
		return $array_data;
    }

    /**
     * 根据条件获取round count
     * @param $arr_where
     * @return int
     * @author yanrui@tizi.com
     */
    public function get_round_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'round_index';
            $str_result_type = 'list';
            $str_fields = TABLE_ROUND.'.id';
            $arr_final_where = array(
                'sale_status' => ROUND_SALE_STATUS_SALE,
                'next_class_begin_time >' => TIME_STAMP,
                'is_live' => 0,
                'is_test' => 0,
            );
            if($arr_where){
                $arr_final_where[TABLE_ROUND.'.stage'] = $arr_where['stage'];
//                unset($arr_where['stage']);
            }
            $arr_where = $arr_final_where;
            $arr_group_by = array(
                TABLE_ROUND.'.id'
            );
            $this->load->model('model/admin/model_round');
            $arr_return = $this->model_round->get_round_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, $arr_order_by = array(), $arr_limit = array());
            $int_return = count($arr_return);
        }
        return $int_return;
    }

    /**
     * 根据条件获取round list
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_round_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'round_index';
            $str_result_type = 'list';
            $str_fields = TABLE_ROUND.'.id,'.TABLE_ROUND.'.title,subtitle,price,sale_price,bought_count,sell_begin_time,sell_end_time,start_time,img,extra_bought_count,course_type,material_version,'.TABLE_ROUND.'.sequence,next_class_begin_time,'.TABLE_USER.'.id as teacher_id,nickname,avatar,'.TABLE_USER_INFO.'.teacher_intro,teacher_age';
//            $str_fields = '*';
            $arr_final_where = array(
                'sale_status' => ROUND_SALE_STATUS_SALE,
                'next_class_begin_time >' => TIME_STAMP,
                'is_live' => 0,
                'is_test' => 0,
            );
            if($arr_where){
                $arr_final_where[TABLE_ROUND.'.stage'] = $arr_where['stage'];
//                unset($arr_where['stage']);
            }
            $arr_where = $arr_final_where;
            $arr_group_by = array(
                TABLE_ROUND.'.id'
            );
            $arr_order_by = array(
                'sequence' => 'desc',
                'next_class_begin_time' => 'asc'
            );
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $this->load->model('model/admin/model_round');
            $arr_return = $this->model_round->get_round_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        }
        return $arr_return;
    }

    /**
     * 获取直播课列表
     * @param $int_per_page
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_live_classes($int_per_page){
        $arr_return = array();
        $str_table_range = 'class_index';
        $str_result_type = 'list';
        $str_fields = TABLE_CLASS.'.id,'.TABLE_CLASS.'.title as class_name,'.TABLE_CLASS.'.begin_time,'.TABLE_CLASS.'.end_time,classroom_id,status,start_time,'.TABLE_ROUND.'.img,'.TABLE_ROUND.'.id as round_id,'.TABLE_ROUND.'.title as round_name';
//            $str_fields = '*';
        $arr_where = array(
            TABLE_ROUND.'.teach_status' => ROUND_TEACH_STATUS_TEACH,
            TABLE_ROUND.'.is_live' => 1,
            TABLE_ROUND.'.is_test' => 0,
            TABLE_CLASS.'.status <' => CLASS_STATUS_CLASS_OVER,
//            TABLE_CLASS.'.is_test' => 0,
            TABLE_CLASS.'.begin_time >' => strtotime(date('Y-m-d',TIME_STAMP)),
        );
        $arr_group_by = array(
            TABLE_CLASS.'.id'
        );
        $arr_order_by = array(
            TABLE_CLASS. '.begin_time' => 'asc'
        );
        $arr_limit = array(
            'start'=> 0,
            'limit' => $int_per_page
        );
        $this->load->model('model/admin/model_class');
        $arr_return = $this->model_class->get_class_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        return $arr_return;
    }
}
