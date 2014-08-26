<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class Course extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_course');
        $this->load->model('business/student/student_order');
    }

    /**
     * 从首页链接到课程购买前页面  获取一个课程下的所有轮
     */
	public function buy_before($int_round_id = 1)
	{
	    $int_round_id = max(intval($int_round_id),1);
	    #检查这个$int_round_id是否存在
	    $bool_flag = $this->student_course->check_round_id_is_exist($int_round_id);
	    if (!$bool_flag){
	        show_error("参数错误");
	    }
	    #检查这个$int_round_id是否在（销售中、已售罄、已停售、已下架）的状态中
		$bool_aflag = $this->student_course->check_round_status($int_round_id);
		if (!$bool_aflag){
			show_error("参数错误哦");
		}
	    #根据$int_round_id获取该轮的部分信息
	    $array_data = $this->student_course->get_round_info($int_round_id);
	    #根据$int_round_id获取该轮的课程大纲
        $array_outline = $this->student_course->get_round_outline($int_round_id);
        #根据$int_round_id获取该轮的课程团队
        $array_team = $this->student_course->get_round_team($int_round_id);
        #根据$int_round_id获取对应课程下的所有轮
        $array_round = $this->student_course->get_all_round_under_course($int_round_id);
        #获取评价总数
        $str_evaluate_count = $this->student_course->get_evaluate_count($int_round_id);
        #判断是否登录以及登陆之后是否买过
        if($this->is_login){
        	#用户登录之后是否买过这轮
        	$int_user_id = $this->session->userdata('user_id');
        	$buy_flag = $this->student_course->check_student_buy_round($int_user_id,$int_round_id);
        	$this->smarty->assign('buy_flag', $buy_flag);
        }
        #seo标题
        if($array_data && $array_data['start_time']){
        	$array_data['seo_time'] = date('n/j',$array_data['start_time']);
        	$array_data['sale_price'] = round($array_data['sale_price']);
        	$array_data['price'] = round($array_data['price']);
        }
        #浏览记录
        $this->student_course->write_recent_view_data($array_data);
        $array_recent_view = array();
        if (CLASSES_INDEX_BROWSING_HISTORY_SWITCH == '1'){
        	$array_recent_view = $this->student_course->read_recent_view_data();
        }       
        
        #重要提醒
        $array_notice = $this->student_course->get_important_notice_data();
        #课程详情页右侧推荐(加上开关)
        $array_other = array();
        $array_recommend = array();
       	if (BUY_BEFORE_RECOMMEND_SWITCH == 1){
       		#该课程系列的其他课程
       		$array_other = $this->student_course->get_other_round_data($int_round_id);
       		#看过本课程的用户还看了
       		$array_recommend = $this->student_course->get_recommend_round_data($int_round_id);
       	}

        #课程列表的地址
        $course_url = config_item('course_url');
        $this->smarty->assign('course_url', $course_url);
        $this->smarty->assign('array_data', $array_data);
        $this->smarty->assign('array_outline', $array_outline);
        $this->smarty->assign('array_team', $array_team);
        $this->smarty->assign('array_round', $array_round);
        $this->smarty->assign('array_other', $array_other);
        $this->smarty->assign('array_recommend', $array_recommend);
        $this->smarty->assign('evaluate_count', $str_evaluate_count);
        $this->smarty->assign('array_recent_view', $array_recent_view);
        $this->smarty->assign('array_notice', $array_notice);
        $this->smarty->assign('material_versions', config_item('material_version'));
        $this->smarty->assign('course_types', config_item('course_type'));
        $this->smarty->display('www/studentMyCourse/buyBefore.html');
	}
	
	/**
	 * 我的评论ajax分页
	 */
	public function ajax_evaluate()
	{
		$data = array();
		$offset = (int) $this->input->post('offset');
		$int_round_id = (int) $this->input->post('round_id');
		$int_total = $this->student_course->get_evaluate_count($int_round_id);
		$this->load->library('pagination');
		$config = config_item('page_user');
		$config['total_rows'] = $int_total;
		$config['per_page'] = PER_PAGE_NO;
		$limit = $offset.",".$config['per_page'];
		$array_evaluate = $this->student_course->get_round_evaluate($int_round_id,$limit);
		$this->pagination->initialize($config);
		$str_page = $this->pagination->createJSlinks('setPage',$offset);
		$this->smarty->assign('array_evaluate', $array_evaluate);
		$this->smarty->assign('page', $str_page);
		$this->smarty->display('www/studentMyCourse/ajax_evaluate.html');
	}
	
	/**
	 * 购买后
	 */
	public function buy_after($int_round_id)
	{
	    #判断是否登录
	    if(!$this->is_login){
	        redirect('/login');
	    }
	    #用户id
	    $int_user_id = $this->session->userdata('user_id');                #TODO用户id
	    #轮id
	    $int_round_id = intval($int_round_id);
	    #检查$round_id以及学生是否购买此轮
	    $bool_flag = $this->student_course->check_student_buy_round($int_user_id,$int_round_id);
	    if (!$bool_flag){
	        show_error("您没有购买此轮课程！");
	    }

	    #课堂同学
	    $array_classmate = $this->student_course->get_classmate_data($int_round_id);
	    #课堂同学总数
	    $int_classmates = count($array_classmate);
	    #课程公告
	    $array_note = $this->student_course->get_class_note_data($int_round_id);
	    #课程大纲
	    $array_outline = $this->student_course->get_round_outline($int_round_id);
	    #即将上课的信息--购买后顶部
	    $array_data = $this->student_course->get_soon_class_data($int_user_id,$int_round_id);
	    #课程列表的地址
	    $course_url = config_item('course_url');
	    $this->smarty->assign('course_url', $course_url);
	    $this->smarty->assign('array_classmate', $array_classmate);
	    $this->smarty->assign('int_classmates', $int_classmates);
	    $this->smarty->assign('array_note', $array_note);
	    $this->smarty->assign('sid', $_COOKIE['NHID']);
	    $this->smarty->assign('array_outline', $array_outline);
	    $this->smarty->assign('array_data', $array_data);
	    $this->smarty->display('www/studentMyCourse/buyAfter.html');
	}
	
	/**
	 * 购买后 查看笔记
	 */
	public function get_user_cloud_notes()
	{
	    #判断是否登录
	    if(!$this->is_login){
	        redirect('/login');
	    }
	    #用户id
	    $int_user_id = $this->session->userdata('user_id');                #TODO用户id
	    #教室id
	    $int_classroom_id = intval($this->input->post('cid'));
	    if (empty($int_classroom_id)){
	        self::json_output(array('status'=>'error','msg'=>'参数错误'));
	    }
	    #根据教室id查找云笔记
	    $array_result = $this->student_course->get_user_cloud_notes($int_classroom_id,$int_user_id);
	    if ($array_result){
	        self::json_output(array('status'=>'ok','data'=>array('content'=>$array_result['content'],
	              'class_title'=>$array_result['class_title'])));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'没获取到您的云笔记'));
	    }
	}
	
	/**
	 * 为课点评
	 */
	public function class_comment()
	{
		#来自于教室的评论 不用判断登陆；来自于购买后评论，需要判断登陆
		$from_type = $this->input->post("from_type");
		if($from_type == '1'){
			#判断是否登录
			if(!$this->is_login){
				self::json_output(array('status'=>'no_login','msg'=>'您还未登陆，请先登录',));
			}
		}

	    $int_user_id = $this->session->userdata('user_id');#TODOuser_id
	    $int_class_id = $this->input->post("class_id");
	    if (empty($int_class_id)){
	        self::json_output(array('status'=>'error','msg'=>'参数错误'));
	    }
	    #根据class_id获取课的信息
	    $array_result = $this->student_course->get_class_infor($int_class_id);
	    if (empty($array_result)){
	        self::json_output(array('status'=>'error','msg'=>'根据课id获取课信息出错'));
	    }
	    $int_score  = $this->input->post("score");
	    $str_content = $this->input->post("content",true);
	    if(intval($int_score) == '0'){
	    	self::json_output(array('status'=>'error','msg'=>'您还没有打分！'));
	    }
	    if(empty($str_content)){
	    	self::json_output(array('status'=>'error','msg'=>'评价内容不能为空！'));
	    }
	    #判断是否有评论过
	    $bool_result = $this->model_course->check_class_comment($int_class_id,$int_user_id);
	    if ($bool_result){
	    	self::json_output(array('status'=>'error','msg'=>'这节课您已经评论过了!'));
	    }
	    $array_data = array(
	            'course_id'=>$array_result['course_id'],
	            'round_id'=>$array_result['round_id'],
	            'class_id'=>$int_class_id,
	            'student_id'=>$int_user_id,
	            'nickname'=>$this->session->userdata('nickname'),
	            'content'=>$str_content,
	            'score'=>  $int_score,
	            'create_time'=>time(),
	            'is_show'=>1
	    );
	    $bool_flag = $this->model_course->save_class_feedback($array_data);
	    if ($bool_flag){
	        self::json_output(array('status'=>'ok','msg'=>'提交评论成功'));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'提交评论失败'));
	    }
	 }
	
	/**
	 * 到核对订单信息页面前的AJAX判断，判断是否有买过该轮
	 */
	public function before_check_order()
	{
	    header('content-type: text/html; charset=utf-8');
	    #判断是否登录
	    if(!$this->is_login){
	        self::json_output(array('status'=>'no_login','msg'=>'您还未登陆，请先登录'));
	    }
	    $int_product_id = $this->input->post("product_id");
	    $int_product_id = max(intval($int_product_id),1);
	    #检查这个$int_product_id是否有效：（在销售中）
	    $bool_flag = $this->student_course->check_round_id($int_product_id);
	    if (!$bool_flag){
	        show_error("参数错误");
	    }
	    #购买前加入没有名额的判断
	    $array_round = $this->model_course->get_round_info($int_product_id);
	    if ($array_round['bought_count'] == $array_round['caps']){
	    	self::json_output(array('status'=>'nerror','msg'=>'这轮已售罄了'));
	    }
	    #购买前加入是否售罄、已停售、已下架
	    if ($array_round['sale_status'] == ROUND_SALE_STATUS_FINISH){
	    	self::json_output(array('status'=>'nerror','msg'=>'这轮已停售了'));
	    }
	    if ($array_round['sale_status'] == ROUND_SALE_STATUS_OFF){
	    	self::json_output(array('status'=>'nerror','msg'=>'这轮已下架了'));
	    }
	    #如果购买的商品已经在订单表存在了，并且状态时已关闭和已取消，则该商品可以继续下单，否则提示它
	    $int_user_id = $this->session->userdata('user_id');                 #TODO
	    $array_result = $this->student_order->check_product_in_order($int_product_id,$int_user_id);
	    if(empty($array_result)){
	        #根据$int_product_id获取订单里面该轮的部分信息
	        self::json_output(array('status'=>'ok','id'=>$int_product_id));
	    }
	    foreach ($array_result as $k=>$v){
    	    switch ($v['status']){
    	        case ORDER_STATUS_INIT:
    	        case ORDER_STATUS_FAIL:
    	            self::json_output(array('status'=>'error','msg'=>'您的订单已经存在，请去订单中心付款',));
    	            break;
    	        case ORDER_STATUS_SUCC:
    	        case ORDER_STATUS_FINISH:
    	        case ORDER_STATUS_APPLYREFUND:
    	        case ORDER_STATUS_APPLYREFUND_FAIL:
    	        case ORDER_STATUS_APPLYREFUND_AGREE:
    	        case ORDER_STATUS_APPLYREFUND_SUCC:
    	            self::json_output(array('status'=>'error','msg'=>'您已经购买过这轮课程，请不要重复购买'));
    	            break;
    	        case ORDER_STATUS_CANCEL:
    	        case ORDER_STATUS_CLOSE:
    	            #根据$int_product_id获取订单里面该轮的部分信息
    	            self::json_output(array('status'=>'ok','id'=>$int_product_id));
    	            break;
	       }
        }
	 }
	 
	 /**
	  * 购买后下载课件
	  */
	 public function courseware()
	 {
	 	header('content-type: text/html; charset=utf-8');
	 	#判断是否登录
	 	if(!$this->is_login){
	 		redirect('/login');
	 	}
	 	$int_class_id = intval($this->uri->rsegment(3));
	 	if (empty($int_class_id)){
	 		show_error('参数错误');
	 	}
	 	#检查用户是否买过这门课 
	 	$int_user_id = $this->session->userdata('user_id');#TODOuser_id
	 	$bool_flag = $this->model_course->check_user_buy_class($int_user_id,$int_class_id);
	 	if(empty($bool_flag)){
	 		show_error('您没有买过这门课，没有下载这门课的权限');
	 	}
	 	#根据课id找课件id
	 	$array_class = $this->model_course->get_class_infor($int_class_id);
	 	if(empty($array_class)){
	 		show_error('抱歉!这节课没有上传课件啊');
	 	}
	 	#课里面有课件，拼接课件地址
	 	$this->load->model('business/common/business_courseware','courseware');
	 	$array_courseware = $this->courseware->get_courseware_by_id(array($array_class['courseware_id']));
	 	if (empty($array_courseware)){
	 		show_error('抱歉!这节课没有上传课件');
	 	}
	 	$wordStr = $array_courseware['0']['download_url'];
	 	$file_name = $array_courseware['0']['name'];
	 	$file_name = urlencode($file_name);
	 	$file_name = str_replace("+", "%20", $file_name);// 替换空格
	 	download($wordStr,$file_name);
	 }
	 

	 
	 /**
	  * Ajax检查是否有评论过
	  */
	 public function ajax_check_comment()
	 {
	 	$int_user_id = $this->session->userdata('user_id');#TODOuser_id
	 	$int_class_id = $this->input->post("class_id");
	 	#判断是否有评论过
	 	$bool_result = $this->model_course->check_class_comment($int_class_id,$int_user_id);
	 	if ($bool_result){
	 		self::json_output(array('status'=>'error','msg'=>'这节课您已经评论过了!'));
	 	} else {
	 		self::json_output(array('status'=>'ok','msg'=>''));
	 	}
	 }
	 
	 /**
	  * 购买后--查看详情
	  */
	 public function buy_detail($int_round_id = 1)
	 {
	 	$int_round_id = max(intval($int_round_id),1);
	 	#检查这个$int_round_id是否存在
	 	$bool_flag = $this->student_course->check_round_id_is_exist($int_round_id);
	 	if (!$bool_flag){
	 		show_error("参数错误");
	 	}
	 	#检查这个$int_round_id是否在（销售中、已售罄、已停售、已下架）的状态中
	 	$bool_aflag = $this->student_course->check_round_status($int_round_id);
	 	if (!$bool_aflag){
	 		show_error("参数错误哦");
	 	}
	 	#根据$int_round_id获取该轮的部分信息
	 	$array_data = $this->student_course->get_round_info($int_round_id);
	 	#根据$int_round_id获取该轮的课程大纲
	 	$array_outline = $this->student_course->get_round_outline($int_round_id);
	 	#根据$int_round_id获取该轮的课程团队
	 	$array_team = $this->student_course->get_round_team($int_round_id);
	 	#根据$int_round_id获取对应课程下的所有轮
	 	$array_round = $this->student_course->get_all_round_under_course($int_round_id);
	 	#获取评价总数
	 	$str_evaluate_count = $this->student_course->get_evaluate_count($int_round_id);
	 	#判断是否登录以及登陆之后是否买过
	 	if($this->is_login)
	 	{
	 		#用户登录之后是否买过这轮
	 		$int_user_id = $this->session->userdata('user_id');
	 		$buy_flag = $this->student_course->check_student_buy_round($int_user_id,$int_round_id);
	 		$this->smarty->assign('buy_flag', $buy_flag);
	 	}
	 	#seo标题
	 	if($array_data && $array_data['start_time'])
	 	{
	 		$array_data['seo_time'] = date('n/j',$array_data['start_time']);
	 		$array_data['start_time'] = date('m月d日',$array_data['start_time']);
	 		$array_data['end_time'] = date('m月d日',$array_data['end_time']);
	 		$array_data['sale_price'] = round($array_data['sale_price']);
	 		$array_data['price'] = round($array_data['price']);
	 	}
	 	
        #浏览记录
	 	$array_recent_view = array();
	 	if (CLASSES_INDEX_BROWSING_HISTORY_SWITCH == '1'){
	 		$array_recent_view = $this->student_course->read_recent_view_data();
	 	}
	 	#重要提醒
	 	$array_notice = $this->student_course->get_important_notice_data();
	    #课程详情页右侧推荐(加上开关)
        $array_other = array();
        $array_recommend = array();
       	if (BUY_BEFORE_RECOMMEND_SWITCH == 1){
       		#该课程系列的其他课程
       		$array_other = $this->student_course->get_other_round_data($int_round_id);
       		#看过本课程的用户还看了
       		$array_recommend = $this->student_course->get_recommend_round_data($int_round_id);
       	}
	 	#课程列表的地址
	 	$course_url = config_item('course_url');
	 	$this->smarty->assign('course_url', $course_url);
	 	$this->smarty->assign('array_data', $array_data);
	 	$this->smarty->assign('array_outline', $array_outline);
	 	$this->smarty->assign('array_team', $array_team);
	 	$this->smarty->assign('array_round', $array_round);
	 	$this->smarty->assign('array_other', $array_other);
	 	$this->smarty->assign('array_recommend', $array_recommend);
	 	$this->smarty->assign('evaluate_count', $str_evaluate_count);
	 	$this->smarty->assign('array_recent_view', $array_recent_view);
	 	$this->smarty->assign('array_notice', $array_notice);
	 	$this->smarty->assign('material_versions', config_item('material_version'));
	 	$this->smarty->assign('course_types', config_item('course_type'));
	 	$this->smarty->display('www/studentMyCourse/buyDetail.html');
	 }
	 

	 /**
	  * 直播课进教室入口
	  */
	 public function enter_live_class($int_class_id,$str_nickname,$int_user_id){

	 	$array_class = $this->model_course->get_class_infor($int_class_id);
	 	if (empty($array_class)){
	 		show_error('参数错误！');
	 	}
	 	$str_classroom_url = '/classroom/main.html?';	 		 	
	 	$array_params = array(
	 			'UserDBID' => $int_user_id,
	 			'ClassID'  => $array_class['classroom_id'],
	 			'UserType' => NH_MEETING_TYPE_STUDENT,
	 	);
	 	$className = !empty($array_class['title']) ? urlencode($array_class['title']) : '';
	 	$UserName = urlencode($str_nickname);
	 	//新增：AES加密flash链接
	 	$uri = http_build_query($array_params);
	 	$aes_config = array(config_item('AES_key'));
	 	$this->load->library('AES', $aes_config, 'aes');
	 	$aes_encrypt_code = urlencode(base64_encode($this->aes->encrypt($uri)));
	 	log_message('debug_nahao', 'classroom uri is: '.$uri.' and the encrypt_code is:'.$aes_encrypt_code);
	 	$str_classroom_url .= 'p='.$aes_encrypt_code.'&UserName='.$UserName.'&ClassName='.$className.'&SwfVer='.(config_item('classroom_swf_version')).'&t=1';
	 	$str_iframe =  $str_iframe = '<iframe src="'.$str_classroom_url.'" width="100%" height="100%" frameborder="0" name="_blank" id="_blank" ></iframe>';
	 	$this->smarty->assign('classroom_id', $array_class['classroom_id']);
	 	$this->smarty->assign('class_id',$array_class['id']);
	 	$this->smarty->assign('iframe', $str_iframe);
	 	$this->smarty->display('www/classRoom/index.html');
	 }
	 
	 /**
	  * 添加直播课的昵称
	  */
	 public function go()
	 {
	 	$int_class_id = $this->input->get('cid');
	 	if ($this->is_login){
	 		$array_infor = $this->_user_detail;
	 		$this->enter_live_class($int_class_id,$array_infor['nickname'],$array_infor['user_id']);
	 	} else{
	 		#没有登录	 		
	 		if(!isset($_GET['token']) || empty($_GET['token'])){
	 			$token = $this->genToken();
	 			$this->load->model('model/common/model_redis', 'redis');
	 			$this->redis->connect('live_class_token');
	 			#token可以用 存在redis里面的value为1；不可以用value为2
	 			$this->cache->redis->set($token,1,'86400');
	 			$this->smarty->assign('token',$token);
	 			$this->smarty->assign('cid',$int_class_id);
	 			$this->smarty->display('www/student_live_class/add_nickname.html');
	 		} else {
	 			$token = $this->input->get("token");
	 			$nickname = $this->input->get("nickname");
	 			$this->load->model('model/common/model_redis', 'redis');
	 			$this->redis->connect('live_class_token');
	 			$value = $this->cache->redis->get($token);
	 			if (empty($value) || $value == '2'){
	 				$token_ag = $this->genToken();
	 				$this->load->model('model/common/model_redis', 'redis');
	 				$this->redis->connect('live_class_token');
	 				#token可以用 存在redis里面的value为1；不可以用value为2
	 				$this->cache->redis->set($token_ag,1,'86400');
	 				$this->smarty->assign('token',$token_ag);
	 				$this->smarty->assign('cid',$int_class_id);
	 				$this->smarty->display('www/student_live_class/add_nickname.html');
	 			} elseif($value == '1') {
	 				$this->load->model('model/common/model_redis', 'redis');
	 				$this->redis->connect('live_class_token');
	 				#token可以用 存在redis里面的value为1；不可以用value为2
	 				$this->cache->redis->set($token,2,'86400');
	 				$int_user_id = 0;
	 				$this->enter_live_class($int_class_id,$nickname,$int_user_id);
	 			}
	 		}
	 	}

	 }
	 
	/**
	 * 生成进入直播课的token
	 * @param int $len
	 * @param boolean $md5
	 */ 
	public function genToken( $len = 32, $md5 = true ) 
	{
	 	# Seed random number generator
	 	# Only needed for PHP versions prior to 4.2
	 	mt_srand( (double)microtime()*1000000 );
	 	# Array of characters, adjust as desired
	 	$chars = array(
	 	'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
	 	'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
	 	'/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
	 	'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
	 	'?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
	 	'=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
	 	'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'
	 	);
	 	# Array indice friendly number of chars;
	 	$numChars = count($chars) - 1; $token = '';
	 	# Create random token at the specified length
	 	for ( $i=0; $i<$len; $i++ )
	 	$token .= $chars[ mt_rand(0, $numChars) ];
	 	# Should token be run through md5?
	 	if ( $md5 ) {
	 	# Number of 32 char chunks
	 	$chunks = ceil( strlen($token) / 32 ); $md5token = '';
	 	# Run each chunk through md5
	 	for ( $i=1; $i<=$chunks; $i++ )
	 		$md5token .= md5( substr($token, $i * 32 - 32, 32) );
	 	# Trim the token
	 	$token = substr($md5token, 0, $len);
	 } return $token;
   }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */