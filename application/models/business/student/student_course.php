<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_Course extends NH_Model{
    
    function __construct(){
        parent::__construct();

        $this->load->model('model/student/model_course');
        $this->load->model('model/student/model_index');
        $this->load->model('model/student/model_index');
        $this->load->model('model/student/model_member');
    }
    
    /**
     * 从首页链接到课程购买前页面  获取一个课程下的所有轮（在销售中）
     * 根据$int_round_id获取对应课程下的所有轮
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_all_round_under_course($int_round_id)
    {
        #根据$int_round_id获取course_id
        $int_course_id = $this->model_course->get_course_id($int_round_id);
        if (!$int_course_id)
        {
            show_error("course错误");
        }
        $array_result = array();
        #根据course_id获取该课程下的所有轮（在销售中）
        $array_result = $this->model_course->get_all_round($int_course_id);
        if ($array_result)
        {
            foreach ($array_result as $k=>$v)
            {
                $array_result[$k]['start_time'] = date("m月d日",$v['start_time']);
                $array_result[$k]['end_time'] = date("m月d日",$v['end_time']);
            }
        }
        return $array_result;
    }
    
    /**
     * 检查这个$int_round_id是否有效（在销售中）
     * @param  $int_round_id
     * @return $bool_return
     */
    public function check_round_id($int_round_id)
    {
        $bool_return = $this->model_course->check_round_id($int_round_id);
        return $bool_return;
    }
    
    /**
     * 根据$int_round_id获取该轮的部分信息
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_round_info($int_round_id)
    {
        $array_return = array();
        #根据$int_round_id获取该轮的部分信息
        $array_return = $this->model_course->get_round_info($int_round_id);
        //var_dump($array_return);die;
        if ($array_return)
        {
            #售罄人数
            $array_return['sold_out_count'] = $array_return['caps'] - $array_return['bought_count'];
            #一轮有几次课
            $class_nums = $this->model_index->round_has_class_nums($int_round_id);
            #课次
            $array_return['class_nums'] = $class_nums;
            #课时总数
            $class_num = $this->model_course->get_calss_hour_totle($int_round_id);
            $array_return['class_hour'] = $class_num['num'];
            #图片地址
            $array_return['class_img'] = empty( $array_return['img']) ? static_url(HOME_IMG_DEFAULT) : get_course_img_by_size($array_return['img'],'large');
            #评分（四舍五入）
            #$array_return['score'] = round($array_return['score']);
            #授课提要
            $array_return['description'] = htmlspecialchars_decode($array_return['description']);
            #课程评分
            $course_score = $this->model_course->get_course_score($array_return['course_id']);
            $array_return['score'] = empty($course_score) ? 0 : round($course_score['score']);
            
        }
        return $array_return;
    }
    
    /**
     * 根据$int_round_id获取该轮的课程大纲
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_round_outline($int_round_id)
    {   
        $array_return = array();
        #获取该轮下的所有章
        $array_chapter = $this->model_course->get_all_chapter($int_round_id);
        #如果有章
        if ($array_chapter)
        {
            foreach ($array_chapter as $k=>$v)
            {
                $array_chapter[$k]['son'] = $this->get_one_chapter_children($v['id'],$int_round_id);
            }
            return $array_chapter;
        } else {
            #没有章，获取该轮下的所有节
            $array_return[] = $this->get_all_section($int_round_id);
            return $array_return;
        }
    }
    
    /**
     * 如果有章，获取下面的节
     * @param  $int_chapter_id
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_one_chapter_children($int_chapter_id,$int_round_id)
    {
        $array_return = array();
        #如果有章，获取下面的节
        $array_return = $this->model_course->get_one_chapter_children($int_chapter_id,$int_round_id);
        //var_dump($array_return);die;
       
        if ($array_return)
        {
            foreach ($array_return as $key=>$val)
            {
                $int_user_id = $this->session->userdata('user_id');
                if($int_user_id){
                    #检查课是否被当前用户评论
                    $boolen_comment = $this->check_class_comment($val['id'],$int_user_id);
                    $array_return[$key]['comment_status'] = $boolen_comment ? 1 :0;
                }
                $stime = $val['begin_time'] ? $val['begin_time'] : 0;
                $etime = $val['end_time'] ? $val['end_time'] : 0;
                 #处理数据
                 $array_return[$key]['time'] = $this->handle_time($stime, $etime);
            }
        }
        return $array_return;
    }
    
    /**
     * 如果没有章，取节
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_all_section($int_round_id)
    {
        $array_return = array();
        $array_result = $this->model_course->get_all_section($int_round_id);
        if ($array_result)
        {
            foreach ($array_result as $key=>$val)
            {
                $int_user_id = $this->session->userdata('user_id');
                if($int_user_id){
                    #检查课是否被当前用户评论
                    $boolen_comment = $this->check_class_comment($val['id'],$int_user_id);
                    $array_result[$key]['comment_status'] = $boolen_comment ? 1 :0;
                }
                $stime = $val['begin_time'] ? $val['begin_time'] : 0;      
                $etime = $val['end_time'] ? $val['end_time'] : 0;
                $array_result[$key]['time'] = $this->handle_time($stime, $etime);
            }
        }
        $array_return['id'] = 1;
        $array_return['title'] = '';
        $array_return['son'] = $array_result;
        return $array_return;
    }
    
    /**
     * 检查课是否被当前用户评论
     * @param  $int_class_id
     * @param  $int_user_id
     * @return $boolen_return 
     */
    public function check_class_comment($int_class_id,$int_user_id)
    {
        $boolen_return = $this->model_course->check_class_comment($int_class_id,$int_user_id);
        return $boolen_return;
    }
    
    /**
     * 处理课程大纲章节开课时间
     * @param  $begin_time
     * @param  $begin_time
     * @return $str
     */
    public function handle_time($begin_time,$end_time)
    {
        $str_year = date("Y-m-d",$begin_time);
        $array_week=array("日","一","二","三","四","五","六");
        $str_day = $array_week[date("w",$begin_time)];
        $str_stime = date("H:i",$begin_time);
        $str_etime = date("H:i",$end_time);
        return $str_year." 星期".$str_day." ".$str_stime."-".$str_etime;
    }
    
    /**
     * 根据$int_round_id获取该轮的课程评价
     * @param  $int_round_id
     * @param  $int_course_id
     * @return $array_return
     */
    public function get_round_evaluate($int_round_id)
    {
        $array_return = array();
        #根据$int_round_id 找course_id
        $int_course_id = $this->model_course->get_course_id($int_round_id);
        if (!$int_course_id)
        {
            show_error("course错误");
        }
        #获取该课程所有评价（取审核通过的5条）
        $array_return = $this->model_course->get_round_evaluate($int_course_id);
        if ($array_return)
        {
            foreach ($array_return as $kk=>$vv)
            {
                #获取用户头像
                $array_return[$kk]['avatar'] = $this->get_user_avater($vv['student_id']);
                #评分（四舍五入）
                $array_return[$kk]['score'] = round($vv['score']);
            }
        }
        return $array_return;
    }
    
    /**
     * 获取课程评价总数
     * @param  $int_round_id
     */
    public function get_evaluate_count($int_round_id)
    {
        #根据$int_round_id 找course_id
        $int_course_id = $this->model_course->get_course_id($int_round_id);
        if (!$int_course_id)
        {
            show_error("course错误");
        }
        $str_evaluate_count = $this->model_course->get_evaluate_count($int_course_id);
        return $str_evaluate_count['num'];
    }
    
    /**
     * 根据$int_round_id获取该轮的课程团队
     * @param  $int_round_id
     * @param  $int_type "-1为所有教师团队"
     * @return $array_return
     */
    public function get_round_team($int_round_id,$int_type= '-1')
    {
        $array_return = array();
        #这个轮里面的所有老师id
        $array_teacher = $this->model_course->get_round_team($int_round_id,$int_type);
        //var_dump($array_teacher);die;
		if($array_teacher)
		{
			#common.php里面的数据字典 老师角色
			$array_teacher_role = config_item('teacher_role');
			//var_dump($array_teacher_role);die;
			#获取老师的具体信息
			foreach ($array_teacher as $k=>$v)
			{
				$array_return[] = $this->model_member->get_user_infor($v['teacher_id']);
				
				if(empty($array_return[$k]))
				{
					log_message('ERROR_NAHAO','['.date('Y-m-d H:i:s').'],老师id为：'.$v['teacher_id']."在user或者userinfo表里面的status =0");
					unset($array_return[$k]);
					break;
				} else {
					$array_return[$k]['teacher_role'] = $array_teacher_role[$v['role']];
					#老师头像
					$array_return[$k]['avatar'] = $this->get_user_avater($v['teacher_id']);
					$array_return[$k]['teacher_intro'] = htmlspecialchars_decode($array_return[$k]['teacher_intro']);
					
				}
			}
		}
        return $array_return;
    }
    
    /**
     * 课堂同学
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_classmate_data($int_round_id)
    {
        $array_return = array();
        #获取用户信息
        $array_data = $this->session->all_userdata();
        #去学生与课的关系表寻找信息
        $array_return = $this->model_course->get_classmate_data($int_round_id);
//         var_dump($array_return);die;
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                #用户信息
                $array_result = $this->model_member->get_user_infor($v['student_id']);
                if (empty($array_result))
                {
                	unset($array_return[$k]);
                	break;
                } else {
                	#处理数据
                	$array_return[$k]['avatar'] = $this->get_user_avater($array_result['user_id']);
                	$array_return[$k]['nickname'] = $array_result['nickname'];
                }
                

            }
        }
        return $array_return;
    }
    
    /**
     * 课程公告
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_class_note_data($int_round_id)
    {
        $array_return = array();
        #去轮公告表寻找信息
        $array_return = $this->model_course->get_class_note_data($int_round_id);
        //var_dump($array_return);die;
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                if ($v['author_role'] == NH_MEETING_TYPE_ADMIN)
                {
                   #发布者是管理员
                    $array_manager = $this->model_member->get_manager_data($v['author']);
                    $array_return[$k]['nickname'] = $array_manager['username'];
                    $array_return[$k]['avatar'] = static_url(DEFAULT_MANGER_AVATER);
                } else {
                    #获取发布者的信息
                    $array_result = $this->model_member->get_user_infor($v['author']);

                    $array_return[$k]['nickname'] = isset($array_result['nickname'])  ? $array_result['nickname'] : '';
                    $array_return[$k]['avatar'] = $this->get_user_avater($array_result['user_id']);
          
                }
                #处理数据
                $array_return[$k]['content'] = htmlspecialchars_decode($v['content']);
            }
        }
        return $array_return;
    }
    
    /**
     * 即将上课的信息--购买后顶部
     * @param  $int_round_id
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_soon_class_data($int_user_id,$int_round_id)
    {
        $array_return = array();
        #获取轮的信息
        $array_round = $this->model_course->get_round_info($int_round_id);
        #获取轮的老师团队
        $array_team = $this->get_round_team($int_round_id);

        #已经上了几节课
        $int_num = $this->model_member->get_class_count(1,$int_round_id);
        #总共有几节课
        $int_totle = $this->model_member->get_class_count(0,$int_round_id);
        #上课节数比例
        $class_rate = $int_totle == 0 ? 0 : round($int_num/$int_totle,2)*100;
        #即将开始的课的信息
        $array_soon = $this->model_course->get_soon_class_data($int_round_id);
        #组合数据
        $array_return['round_id'] = $array_round['id'];					#轮的id
        $array_return['teach_status'] = $array_round['teach_status'];	#轮的授课状态
        $array_return['title'] = $array_round['title'];					#轮的标题
        $array_return['team'] = $array_team;							#教室团队
        $array_return['class'] = $int_num; 								#已经上了几节课
        $array_return['class_rate'] = $class_rate;						#上课节数比例
       
       
        $array_return['soon_class_infor'] = $array_soon;
        
/*         $array_return['classroom_id'] = $array_soon ? $array_soon['classroom_id'] : '';		#教室id
        $array_return['status'] = $array_soon ? $array_soon['status'] : '';					#课的状态
        $array_return['soon_class_title'] = $array_soon ? $array_soon['title'] : '';		#即将开始课的节
        $array_return['soon_class_stime'] = $array_soon ? $array_soon['begin_time'] : '';	#课的开始时间 */
        return $array_return;
    }
    
    /**
     * 检查学生是否购买此轮
     * @param  $int_user_id
     * @param  $int_round_id
     * @return $bool_return
     */
    public function check_student_buy_round($int_user_id,$int_round_id)
    {
        $bool_return = $this->model_course->check_student_buy_round($int_user_id,$int_round_id);
        return $bool_return;
    }
    
    /**
     * 获取云笔记
     * @param  $int_classroom_id
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_user_cloud_notes($int_classroom_id,$int_user_id)
    {
        $array_return = array();
        #获取云笔记
        $array_return = $this->model_course->get_user_cloud_notes($int_classroom_id,$int_user_id);
        if ($array_return){
            #获取云笔记对应的课的标题
            $array_class = $this->model_course->get_user_cloud_notes_class($array_return['classroom_id']);
            
            #处理数据
            $array_return['class_title'] = $array_class['title'];
        }

        return $array_return;
    }
    
    /**
     * 根据课的id获取课的信息
     * @param  $int_class_id
     * @param  $array_return
     */
    public function get_class_infor($int_class_id)
    {
        $array_return = array();
        $array_return = $this->model_course->get_class_infor($int_class_id);
        return $array_return;
    }
    
    /**
     * 获取用户头像
     */
    public function get_user_avater($int_user_id)
    {
    	$avatar = static_url(DEFAULT_STUDENT_AVATER);
    	$array_return  = $this->model_member->get_user_avater($int_user_id);
    	if ($array_return)
    	{
    		if ($array_return['avatar'])
    		{
    			$avatar = NH_QINIU_URL.$array_return['avatar'];
    		} else {
    			if ($array_return['teach_priv'] == 1){
    				$avatar = static_url(DEFAULT_TEACHER_AVATER);
    			} else{
    				$avatar = static_url(DEFAULT_STUDENT_AVATER);
    			}
    		}
    	}

    	return $avatar;
    }
    
    /**
     * 判断是否是这节课的老师
     * @param  $int_user_id
     * @param  $int_class_id
     * @return boolean
     */
    public function check_is_teacher_in_class($int_user_id,$int_class_id)
    {
    	$array_class = $this->model_course->get_class_infor($int_class_id);
    	if($array_class)
    	{
    		$bool_flag = $this->model_course->check_is_teacher_in_class($int_user_id,$array_class['round_id']);
    		return $bool_flag;
    	}
    }
    
    /**
     * 检查这个$int_round_id是否有效（轮id是否存在）
     * @param  $int_round_id
     * @return $bool_return
     */
    public function check_round_id_is_exist($int_round_id)
    {
    	$bool_return = $this->model_course->check_round_id_is_exist($int_round_id);
    	return $bool_return;
    }
    
    /**
     * 检查这个$int_round_id是否在（销售中、已售罄、已停售、已下架）的状态中
     * @param  $int_round_id
     * @return $bool_return
     */
    public function check_round_status($int_round_id)
    {
    	$bool_return = $this->model_course->check_round_status($int_round_id);
    	return $bool_return;
    }
}