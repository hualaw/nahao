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
    	#1. 获取分类结构
    	$cateList = $this->business_list->getCateList($param);
    	#2. 设置参数初始值
    	$num = LIST_NUM;
    	$num = 3;
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
    	$result = $this->business_list->search_suggest(array('typeId' => $param['typeId']));
    	#6. 浏览记录
    	$this->smarty->assign('seo' 			, $seo);
    	$this->smarty->assign('cateList' 		, $cateList);
    	$this->smarty->assign('list' 			, $data['data']);
    	$this->smarty->assign('suggest_list' 	, $result['data']);
    	$this->smarty->assign('pageBar' 		, $pageBar);
    	$this->smarty->display('www/student_class/index.html');
    }
    // 课程列表----学科辅导
    function xueke(){
    	$this->smarty->display('www/student_class/index.html');
    }
    // 课程列表----素质教育
    function suzhi(){
    	$this->smarty->display('www/student_class/index.html');
    }
    // 课程详情页面
    function article(){
    	$this->smarty->display('www/student_class/article.html');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */