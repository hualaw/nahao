<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tools相关逻辑
 * Class Business_Tools
 */
class Business_Tools extends NH_Model
{   
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_tools');
    }
    
    public function error_output($msg)
    {
    	$arr_response = array(
	        'status' => 'error',
	        'msg' => $msg ? $msg : '操作失败',
			);
    	echo json_encode($arr_response);
    	exit;
    }
    
    /**
     * 应用1：【随到随买】临时添加学生下单买课，【含下订单】
     */
    public function student_order_APP($param)
    {
    	if(empty($param['round_id']) || empty($param['user_id'])){
    		$this->error_output('缺少轮id或用户id~');
    	}
    	$user_id = $param['user_id'];
    	$round_id = $param['round_id'];
    	#1. 查询此轮信息，含已购买数，是否达到上限，是就终止提示不可购买，不是就继续
    	$round_info = $this->model_tools->round_info_searcher($param);
    	$round_info = $round_info[0];
    	if($round_info['bought_count'] >= $round_info['caps']){
    		$this->error_output('购买失败：已购买人数已经达到上限（售罄）~');
    	}
    	#2. 查询此轮的授课状态，如果是 【等待开课/初始化，授课中】，是就终止提示不可购买，不是就继续
    	if(!in_array($round_info['teach_status'],array(ROUND_TEACH_STATUS_INIT,ROUND_TEACH_STATUS_TEACH))){
    		$this->error_output('购买失败：该班次允许购买的授课状态已过，不可购买');
    	}
    	#3. 查询学生与轮是否有状态为2（已支付）的订单记录，有则终止提示，没有就继续
    	$is_pay = $this->model_tools->search_student_order(array('round_id'=>$round_id,'user_id'=>$user_id));
    	if($is_pay>0){
    		$this->error_output('购买失败：该学生买过该课了~');
    	}
    	
    	#4. 查找用户信息，看看是否有phoneserver手机号，没有就终止，没有则继续
//    	$phone = get_pnum_phone_server($user_id);
//    	if(empty($phone)){
//    		$this->error_output('该学生没有验证过手机号~');
//    	}
		$round_info['now_price'] = '';
    	#5. 【如果第1,2,3步成功】					生成学生与订单记录，计算当前可购买数比例，根据 原销售价sale_price
		if($round_info['sale_price']<=0){
			#5.1 如果是<=0，免费课，现价为0元
			$round_info['now_price'] == 0;
		}elseif($round_info['sale_price']>0 && $round_info['sale_price']<=1){
			#5.2 如果是=1，1元课，现价为1元
			$round_info['now_price'] == 1;
		}else{
			#5.3 如果是>0 <1，根据可购买比例计算现价
			$input = array(
   				'round_id'		=> $round_id,
   				'class_count'	=> $round_info['class_count'],
   				'sale_price'	=> $round_info['sale_price']
   				);
			$counter = $this->get_round_rate_price($input);
   			$round_info['now_price'] = $counter['now_price'];
		}
		
		#5.4 生成订单【生成时间和支付时间都是当前时间】【支付类型：4，线下】【状态：2已付款】
		$param = array(
				'round_id' 	=> $round_id,
				'user_id' 	=> $user_id,
				'now_price'	=> $round_info['now_price'],
				'sale_price'=> $round_info['sale_price'],
			);
		$insert_id = $this->model_tools->student_order_maker($param);
    	#6. 【如果第5.4步成功】：                     创建订单日志，修改轮购买人数，修改用户类型,生成学生与课的记录。
		$data = array(
			'msg' 		=> '',
			'status' 	=> '',
			);
    	if($insert_id){
    		$data['status'] = 'ok';
    		#6.1 生成学生下单日志记录2条：1.创建订单成功  2.  xxxx元课程支付成功/支付成功
    		$param = array(
    				'user_id' 	=> $user_id,
    				'now_price'	=> $round_info['now_price'],
    				'order_id'	=> $insert_id,
    			);
    		$res = $this->model_tools->order_log_maker($param);
    		$data['msg'] 	.= '订单日志记录成功!<br>';
    		#6.2 修改用户类型 如果轮的售价大于0 此处应该更改用户的付费类型为付费用户
    		if($round_info['now_price']>0){
    			$res = $this->model_tools->userinfo_upgrader(array('user_id'=>$user_id));
    			$data['msg'] 	.= '用户升级为付费用户!<br>';
    		}
    		#6.3 轮购买人数+1
    		$res = $this->model_tools->round_upgrader(array('round_id'=>$round_id));
    		if($res){
    			$data['msg'] 	.= '轮总购买次数加1!<br>';
    		}
    		#6.4 根据【查询可购买课，状态0,1 初始化，即将开课】生成学生与课的记录,初始化状态
    		$allow_arr = $this->model_tools->round_allow_class(array('round_id'=>$round_id));
    		
    		if(count($allow_arr)>0){
    			foreach ($allow_arr as $val){
    				$param = array(
    					'user_id' 	=> $user_id,
    					'round_id' 	=> $round_id,
    					'course_id' => $round_info['course_id'],
    					'class_id' 	=> $val['id'],
    					);
    				$res = $this->model_tools->student_class_maker($param);
    				$data['msg'] .= $res ? '购买课id：'.$val['id'].'成功!<br>' : '购买课id：'.$val['id'].'失败!<br>';
    			}
    		}
		}else{
			$data['status'] = 'error';
			$data['msg'] 	= '下订单失败!';
		}
		return $data;
    }
    
    /**
     * 应用2:搜索学生昵称,返回id
     */
   	public function search_student($param)
   	{
   		if(empty($param['nickname']) && empty($param['user_id'])){exit('缺少学生昵称，无法搜索');}
   		$user_info = $this->model_tools->user_info_searcher($param);
   		
   		return $user_info;
   	}
   	
   	/**
   	 * 应用3：搜索轮名，返回轮信息
   	 */
   	public function search_round($param)
   	{
   		if(empty($param['round_name']) && empty($param['round_id'])){exit('缺少轮名和轮id，无法搜索');}
   		$round_info = $this->model_tools->round_info_searcher($param);
   		if(!empty($param['round_id'])){
	   		foreach($round_info as &$val){
	   			#图片地址
	       		$val['round_img'] = empty($val['img']) ? static_url(HOME_IMG_DEFAULT) : get_img_url($val['img'],'course_s4');
	   			
	       		#计算进度与现价
	   			$input = array(
	   				'round_id'=>$val['id'],
	   				'class_count'=>$val['class_count'],
	   				'sale_price'=>$val['sale_price']
	   				);
	       		$counter = $this->get_round_rate_price($input);
	       		//可购买数比例
	   			$val['rate'] = $counter['rate'];
	   			//现价
	   			$val['now_price'] = $counter['now_price'];
	   		}
   		}
        return $round_info;
   	}
   	
   	/**
	 * 应用4:计算一个轮的当前购买数比例 与 当前购买价格
	 * 参数：轮id，总课数，原销售价格
	 */ 
   	public function get_round_rate_price($param)
   	{
   		if(empty($param['round_id']) || empty($param['class_count'])){
   			$this->error_output('缺少轮进度统计参数~');
   		}
   		$param['sale_price'] = !empty($param['sale_price']) ? $param['sale_price'] : 0;
   		$arrow = $this->model_tools->round_allow_num(array('round_id'=>$param['round_id']));
   		#允许购买课数量
   		$num = $arrow[0]['num'];
   		$rate_price = array(
   				'rate' 		=> '',
   				'now_price' => '',
   			);
   		//购买数比例
   		$rate_price['rate'] 		= '<b>'.$num.'</b>/'.$param['class_count'];
   		//现价
   		$rate_price['now_price'] 	= round(($num/$param['class_count'])*$param['sale_price'],2);
   		return $rate_price;
   	}

   	/**
	 * 为老师设置代理服务器
	 */ 
   	public function set_proxy($param){
   		$res = $this->model_tools->set_teacher_proxy($param);
   		return $res;
   	}
   	
   	/**
   	 * 取消老师代理服务器
   	 */
   	public function unset_proxy($param){
   		$res = $this->model_tools->unset_teacher_proxy($param);
   		return $res;
   	} 
   	
   	/**
	 * 查找已经设置过代理服务器的老师
	 */ 
   	public function get_proxy_teacher(){
   		 $res = $this->model_tools->get_proxy_teacher();
   		 $mcu = config_item('McuAddr');
   		 if($res) foreach ($res as &$val){
   		 	$val['proxy_addr'] = $mcu[$val['proxy']];
   		 }
   		 return $res;
   	}
}