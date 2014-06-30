<?php
    class Business_crontab extends NH_Model
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/auto/model_crontab');
        }

        /**
         * 获取课的id
     	 * type=1 where条件为begin_time
     	 * type=2 where条件为end_time
         * @param  $int_time
         * @param  $type
         * @return $array_return
         */
        public function get_class_data($int_time,$type)
        {
        	$array_return = array();
        	$array_return = $this->model_crontab->get_class_data($int_time,$type);
        	return $array_return;
        }
        
        /**
         * 更新课的状态
         * @param  $array_update
         * @param  $array_where
         * @return $bool_return
         */
        public function update_class_status($array_update,$array_where)
        {
        	$bool_return = $this->model_crontab->update_class_status($array_update,$array_where);
        	return $bool_return;
        }
        
        /**
         * 一节课上完了，寻找下节课
         * @param  $int_class_id
         * @param  $int_round_id
         * @return $next_class_id
         */
        public function get_next_class_data($int_class_id,$int_round_id)
        {
        	$array_result = array();
        	#获取该节课对应轮下的所有章数组
        	$array_charpter = $this->model_course->get_all_chapter($int_round_id);
        	#有章
        	if($array_charpter)
        	{
        		#获取下面的节
        		$array_section = $this->model_crontab->get_section_data($int_round_id);
        	} else {
        		#没有章
        		$array_section = $this->model_course->get_all_section($int_round_id);
        	}
			$array_class_id = array();
        	if ($array_section)
        	{
        		foreach ($array_section as $kk=>$vv)
        		{
        			$array_class_id[] = $vv['id'];
        		}
        		$array_key = array_search($int_class_id,$array_class_id);
        		if(isset($array_class_id[$array_key+1]))
        		{
        			$next_class_id = $array_class_id[$array_key+1];
        		} else {
        			$next_class_id = '';
        		}
        		return $next_class_id;
        	}
        }
        
        /**
         * 寻找每一节课老师按开始上课按钮的时间
         * @param  $int_classroom_id
         */
        public function get_time_by_teacher_press_submit($int_classroom_id)
        {
        	$array_return = array();
        	$array_return = $this->model_crontab->get_time_by_teacher_press_submit($int_classroom_id);
        	return $array_return;
        }

        /**
         * 根据课id找出上过这节课的学生
         * @param  $int_classroom_id
         */
        public function get_enter_classroom_user_id($int_classroom_id)
        {
        	$array_return = array();
        	$array_return = $this->model_crontab->get_enter_classroom_user_id($int_classroom_id);
        	return $array_return;
        }
        
        /**
         * 根据课id在student_class表里面找到买过这节课的学生
         * @param  $int_class_id
         */
        public function get_buy_class_user($int_class_id)
        {
        	$array_return = array();
        	$array_return = $this->model_crontab->get_buy_class_user($int_class_id);
        	return $array_return;
        }

        /**
         *轮的状态
         * @author shangshikai@tizi.com
         */
        public function round_change_status()
        {
            $time=time();

            $year=date('Y',$time);
            $mon=date('m',$time);
            $day=date('d',$time);
            $hour=date('H',$time);
            $min=date('i',$time);
            $advance_time=mktime($hour,$min,0,$mon,$day,$year);
            $advance_time=strtotime($advance_time);

            $all_round_status=$this->model_crontab->all_round_status();
            $all_run_status=$this->model_crontab->all_round_teach();

            foreach($all_round_status as $v)
            {
                $round_begin_minutes=$v['sell_begin_time'];
                $round_end_minutes=$v['sell_end_time'];

                if($round_begin_minutes==$advance_time)
                {
                    return $this->model_crontab->status_begin_round($v['id']);
                }
                if($round_end_minutes==$advance_time)
                {
                    return $this->model_crontab->status_end_round($v['id']);
                }
            }

            foreach($all_run_status as $v)
            {
                $round_start_minutes=$v['start_time'];
                $round_end=$v['end_time'];
                $mon_year=date('Y',$round_end);
                $mon_mon=date('m',$round_end);
                $mon_day=date('d',$round_end);
                $mon_hour=date('H',$round_end);
                $mon_min=date('i',$round_end);
                $mon_second=date('s',$round_end);
                $mon_time=mktime($mon_hour,$mon_min,$mon_second,$mon_mon+1,$mon_day,$mon_year);

                if($round_start_minutes==$advance_time)
                {
                    return $this->model_crontab->status_start_round($v['id']);
                }
                if($round_end==$advance_time)
                {
                    return $this->model_crontab->end_round($v['id']);
                }
                if($mon_time<=$time)
                {
                    return $this->model_crontab->expire_round($v['id']);
                }
            }
        }
    }