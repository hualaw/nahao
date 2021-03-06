<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_Member extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_member');
        $this->load->model('model/student/model_course');
        $this->load->model('business/student/student_course');
        $this->load->model('business/student/student_order');
    }

    /*
    public function get_my_course_for_buy($int_user_id)
    {
        $array_return = array();
        $array_return = $this->model_member->get_my_course_for_buy($int_user_id);
        //var_dump($array_return);die;
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                #图片地址
                $class_img = empty( $v['img']) ? static_url(HOME_IMG_DEFAULT) : get_img_url($v['img'],'course_s4');
                #这轮共M节
                $totle_class = $this->model_member->get_class_count(0,$v['round_id']);
                #这轮上了M节
                $class  = $this->model_member->get_class_count('1',$v['round_id']);
                #比例 = 上了M节/共M节
                $class_rate = $totle_class == 0 ? 0 : round($class/$totle_class,2)*100;
                #授课状态
//                 if ($v['teach_status'] >=0 && $v['teach_status'] <=1)
//                 {
                #下节课上课时间
                $next_class_time = $this->model_member->get_next_class_time($v['round_id']);
                if($next_class_time)
                {
                    $stime = $next_class_time['begin_time'];
                    $etime = $next_class_time['end_time'];
                    #处理下节课上课时间
                    $array_return[$k]['next_class_time'] = $this->student_course->handle_time($stime,$etime);
                } else {
                    #处理下节课上课时间
                    $array_return[$k]['next_class_time'] = '';
                }

//                 } else{
//                 	$array_return[$k]['next_class_time'] = '';
//                 }
                #组合数据
                $array_return[$k]['class_img'] = $class_img;
                $array_return[$k]['totle_class'] = $totle_class;
                $array_return[$k]['class'] = $class;
                $array_return[$k]['class_rate'] = $class_rate;
            }
        }
        //var_dump($array_return);die;
        return $array_return;
    }
    */

    public function get_my_course_by_where($int_user_id,$status = '',$offset = 0,$per_page = PER_PAGE_NO)
    {
        $result = array();
        $my_course_total = $this->model_member->get_my_course_total($int_user_id,$status);

        $array_return = $this->model_member->get_my_course_for_buy_by_where($int_user_id,$status,$offset,$per_page);

//        var_dump($array_return);die;
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                #图片地址
                $class_img =  $v['img'];
                #这轮共M节
                $totle_class_array = T(TABLE_CLASS)->getFields(array('round_id','status'),'round_id = '.$v['round_id']);
                $totle_class = count($totle_class_array);
//                print_r($totle_class);
                $my_course = array();
                if(!empty($totle_class_array)){
                    foreach($totle_class_array as $v){
                        if($v['status'] == CLASS_STATUS_CLASS_OVER || $v['status'] == CLASS_STATUS_MISS_CLASS){
                            $my_course[] = $v;
                        }
                    }
                }
                $class = count($my_course);

                #比例 = 上了M节/共M节
                $class_rate = $totle_class == 0 ? 0 : round($class/$totle_class,2)*100;
                #授课状态
//                 if ($v['teach_status'] >=0 && $v['teach_status'] <=1)
//                 {
                #下节课上课时间
                $next_class_time = $this->model_member->get_next_class_time($v['round_id']);
                if($next_class_time)
                {
                    $stime = $next_class_time['begin_time'];
                    $etime = $next_class_time['end_time'];
                    #处理下节课上课时间
                    $array_return[$k]['next_class_time'] = $this->student_course->handle_time($stime,$etime);
                } else {
                    #处理下节课上课时间
                    $array_return[$k]['next_class_time'] = '';
                }

//                 } else{
//                 	$array_return[$k]['next_class_time'] = '';
//                 }
                #组合数据
                $array_return[$k]['class_img'] = $class_img;
                $array_return[$k]['totle_class'] = $totle_class;
                $array_return[$k]['class'] = $class;
                $array_return[$k]['class_rate'] = $class_rate;
            }
        }
        //var_dump($array_return);die;
        $result['total'] = $my_course_total;
        $result['list'] = $array_return;
        return $result;
    }
    
    /**
     * 我的订单列表
     * @param  $int_user_id
     * @param  $str_type
     * @return $array_return
     */
    public function get_order_list($int_user_id,$str_type,$int_start,$int_limit)
    {
        $array_return = array();
        $array_return = $this->model_member->get_order_list($int_user_id,$str_type,$int_start,$int_limit);
        //var_dump($array_return);die;
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                #获取轮的信息
                $array_round = $this->model_course->get_round_info($v['round_id']);
                if(empty($array_round)){
                	unset($array_return[$k]);
                	continue;
                }
                #处理时间
                $array_return[$k]['create_time'] = date('Y/m/d H:i:s',$v['create_time']);
                $array_return[$k]['class_img'] = $array_round['img'];
                $array_return[$k]['title'] = $array_round['title'];
                $array_return[$k]['teach_status'] = $array_round['teach_status'];
                #处理付款方式
                if ($v['pay_type'] == '0'|| $v['pay_type'] == '1' ||$v['pay_type'] == '2'||$v['pay_type'] == '3')
                {
                    $array_return[$k]['payment_method'] = 'online';
                } elseif ($v['pay_type'] == '4') {
                    $array_return[$k]['payment_method'] = 'remittance';
                }
            }
        }
        return $array_return;
    }
    
    /**
     * 获取订单的总数
     * @param  $int_user_id
     * @param  $str_type
     * @return $array_return
     */
    public function get_order_count($int_user_id,$str_type)
    {
        $array_return = array();
        $array_return = $this->model_member->get_order_count($int_user_id,$str_type);
        if ($array_return)
        {
        	foreach ($array_return as $k=>$v)
        	{
        		$array_round = $this->model_course->get_round_info($v['round_id']);
        		if (empty($array_round))
        		{
        			unset($array_return[$k]);
        			continue;
        		}
        	}
        }
        $int_return = count($array_return);
		return $int_return;
    }
    
    /**
     * 获取申请退课数据
     * @param  $int_round_id
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_apply_refund_data($int_user_id,$array_order)
    {
        $array_return = array();
        #获取轮的信息
        $array_round = $this->model_course->get_round_info($array_order['round_id']);
        #这个人买的这轮上了N节
        $array_return['class'] = $this->model_member->get_student_class_done($int_user_id,$array_order['round_id']);
        #这个人买的这轮总共M节
        $array_return['totle_class'] = $this->model_member->get_student_class_totle($int_user_id,$array_order['round_id']);
        #获取轮的标题
        $array_return['title'] = $array_round['title'];
        #课程总金额
        $array_return['totle_money'] = $array_order['spend'];
        #每节课的价格       
        $per_price = $array_return['totle_money']/$array_return['totle_class'];
        $per_price = explode('.', $per_price);
        $array_return['per_price'] = $per_price['0'];
        #这个人买的这轮没上N节
        $array_return['unclass'] = $this->model_course->get_uncalss_count($array_order['round_id']);
        #可退金额
        $array_return['return_money'] = $array_return['per_price']* $array_return['unclass'];
        #轮id
        $array_return['round_id'] = $array_round['id'];
        #轮的授课状态
        $array_return['teach_status'] = $array_round['teach_status'];
        //var_dump($array_return);die;
        return $array_return;
    }

    /**
     * 添加退课申请
     * @param  $array_data
     */
    public function save_refund($array_data)
    {
        #添加到退款记录表
        $array_add_data = array(
            'round_id'=>$array_data['round_id'],
            'student_id'=>$array_data['student_id'],
            'refund_count'=>$array_data['refund_count'],
            'study_count'=>$array_data['study_count'],
            'round_price'=>$array_data['round_price'],
            'refund_price'=>$array_data['refund_price'],
            'create_time'=>time(),
            'status'=>REFUND_STATUS_INIT,
            'order_id'=>$array_data['order_id'],
            'reason'=>$array_data['reason']
        );
        $aflag = $this->model_member->add_student_refund($array_add_data);
        #更改订单表的状态,并且写日志
        $array_mdata = array(
            'user_id'=>$array_data['student_id'],
            'order_id'=>$array_data['order_id'],
            'status'=>ORDER_STATUS_APPLYREFUND,
            'action'=>ORDER_ACTION_APPLY_REFUND,
            'note'=>'申请退课',
            'user_type'=>NH_MEETING_TYPE_STUDENT,
            'pay_type'=>$array_data['pay_type']
        );
        $mflag = $this->student_order->update_order_status($array_mdata);

        $array_ndata = array(
        		'round_id'=>$array_data['round_id'],
        		'student_id'=>$array_data['student_id']
        );
        #找到该学生没有上课的课id
        $array_class = $this->model_member->get_student_class_undone($array_ndata);
        #更改学生与课的关系表，将该学生没有上过的课里面的状态更为申请退款
        if ($array_class)
        {
        	foreach ($array_class as $k=>$v)
        	{
        		$array_where = array(
        				'class_id'=>$v['class_id'],
        				'student_id'=>$array_data['student_id']
        		);
        		$array_update = array(
        				'status'=>STUDENT_CLASS_APPLY_REFUND
        		);
        		$this->model_member->update_student_class($array_update,$array_where);
        	}
        }

        #更改用户的银行卡信息
        $array_bank_where = array(
            'user_id'=>$array_data['student_id']
        );
        $array_bank_update = array(
            'bankname'=>$array_data['bankname'],
            'bankbench'=>$array_data['bankbench'],
            'bankcard'=>$array_data['bankcard'],
            'id_code'=>$array_data['id_code'],
        );
        $kflag = $this->model_member->update_user_bank_infor($array_bank_update,$array_bank_where);
        if ($aflag && $mflag  && $kflag)
        {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 获取一轮的退款详情
     * @param  $int_user_id
     * @param  $int_round_id
     * @return $array_data
     */
    public function get_student_refund_data($int_user_id,$array_order)
    {
        #获取轮的数据
        $array_round = $this->student_course->get_round_info($array_order['round_id']);
        #获取学生退款记录
        $array_data = $this->model_member->get_student_refund_data($int_user_id,$array_order['round_id']);
        $array_data['title'] = $array_round['title'];
        return $array_data;
    }

}