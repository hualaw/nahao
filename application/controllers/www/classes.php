<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class Classes extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model("business/common/business_list");
//        $this->load->model('business/student/student_course');
//        $this->load->model('business/student/student_order');
    }

    // 课程列表
    function index($queryStr=''){
    	parse_str(str_replace('?','',$queryStr),$param);
    	if(empty($param['typeId'])){exit('类型参数不能为空');}
    	#0. 新加标签往期属性
    	$param['kindId'] = $this->input->get('kindId');
    	$param['kindId'] = !empty($param['kindId']) ? $param['kindId'] : 1;
    	#1. 获取分类结构
    	$cateList = $this->business_list->getCateList($param);
    	#2. 设置参数初始值
    	$num = LIST_NUM;
//    	$num = 3;
    	$param['order'] 	= !empty($param['order']) ? $param['order'] : 1;
    	$param['page'] 		= !empty($param['page']) ? $param['page'] : 1;
    	$param['num'] 		= !empty($param['num']) ? $param['num'] : $num;
    	$data = $this->business_list->search($param);
    	$this->smarty->assign('param' , $param);
    	#3. 生成seo标题与面包屑
    	$seo = $this->business_list->process_SEO($param);
    	#4. 分页
    	$page = $param['page'];
    	unset($param['page']);
    	$config = array(
    		'total' 	=> $data['total'],
    		'num' 		=> $num,
    		'page' 		=> $page,
    		'base_link' => $this->business_list->getLink($param),
    	);
    	$pageBar = $this->business_list->getPageBar($config);
    	#5. 搜索推荐-猜您喜欢
    	if(CLASSES_INDEX_SUGGEST_SWITCH){
    		$result = $this->business_list->search_suggest(array('typeId' => $param['typeId']));
    		$suggest_list = $result['data'];
    	}else{
    		$suggest_list = 0;
    	}
    	#6. 浏览记录
    	if(CLASSES_INDEX_BROWSING_HISTORY_SWITCH){
    		$view_list = $this->business_list->read_recent_view_data();
    	}else{
    		$view_list = 0;
    	}
    	#7. 没有记录，就读取推荐
    	$list = $data['is_commend'] ? $data['commend_data'] : $data['data'];
    	
    	$course_url = config_item('course_url');
    	$this->smarty->assign('course_url', $course_url);
    	$this->smarty->assign('seo' 				, $seo);
    	$this->smarty->assign('cateList' 			, $cateList);
    	$this->smarty->assign('is_commend' 			, $data['is_commend']);
    	$this->smarty->assign('list' 				, $list);
    	$this->smarty->assign('suggest_list' 		, $suggest_list);
    	$this->smarty->assign('pageBar' 			, $pageBar);
    	$this->smarty->assign('array_recent_view' 	, $view_list);
    	$this->smarty->assign('body_class'		 	, $param['typeId']==1 ? 'navTutor' : 'navQualityEdu');
    	$this->smarty->display('www/student_class/index.html');
    }
    
    // 课程详情页面
//    function article(){
//    	$this->smarty->display('www/student_class/article.html');
//    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */