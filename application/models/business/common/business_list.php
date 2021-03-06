<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * jason
 */
class Business_List extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_list');
        $this->load->model('model/student/model_member');
    }
	
    /**
     * 获取分类
     */
    function getCateList($param){
    	if(empty($param['typeId'])){exit('类型参数不能为空');}
    	$param['qualityId'] = !empty($param['qualityId']) ? $param['qualityId'] : '';
    	$param['stageId'] 	= !empty($param['stageId']) ? $param['stageId'] : '';
    	$param['gradeId'] 	= !empty($param['gradeId']) ? $param['gradeId'] : '';
    	$param['order'] 	= !empty($param['order']) ? $param['order'] : 1;
    	$param['subjectId'] = !empty($param['subjectId']) ? $param['subjectId'] : '';
    	#1.获取配置
    	$kind = config_item('kind');
    	$cate = config_item('cate');
    	$cate_stage = config_item('cate_stage');
    	$cate_grade = config_item('cate_grade');
    	$cate_subject = config_item('cate_subject');
    	$cate_quality = config_item('cate_quality');
    	$cateArr = array();
    	#2.类型
    	//2.0 学习方式
    	$cateArr['kindArr'] = $kind;
    	foreach($cateArr['kindArr'] as $key=> &$val){
    		$val['url'] = $this->getLink(array('typeId' => $param['typeId'],'kindId' => $key,'order'=>$param['order']));
    		$val['is_active'] = $key == $param['kindId'] ? 1 : 0;
    	}
    	//2.1 教育类型
    	$cateArr['typeArr'] = $cate;
    	foreach($cateArr['typeArr'] as $key=> &$val){
    		$val['url'] = $this->getLink(array('typeId' => $key,'order'=>$param['order'],'kindId' =>$param['kindId']));
    		$val['is_active'] = $key == $param['typeId'] ? 1 : 0;
    	}
    	#生成类别数组
    	if($param['typeId'] == SUBJECT_STUDY){
    		//2.2 学段
    		$cateArr['stageArr'] = $cate_stage;
    		foreach($cateArr['stageArr'] as $key=> &$val){ //key [stageId]
	    		$val['url'] = $this->getLink(array('typeId' => $param['typeId'],'stageId' => $key,'order'=>$param['order'],'kindId' =>$param['kindId']));
	    		$val['is_active'] = $key == $param['stageId'] ? 1 : 0;
	    	}
    		//2.3 年级
    		$cateArr['gradeArr'] = $cate_grade;
    		$filter_grade = !empty($param['stageId']) ? $cate_stage[$param['stageId']]['chirdren'] : array();
    		foreach($cateArr['gradeArr'] as $key =>&$val){ //key [gradeId]
	    		if($filter_grade && !in_array($key,$filter_grade)){
	    			unset($cateArr['gradeArr'][$key]);
	    		}else{
	    			$val['url'] = $this->getLink(array('typeId' => $param['typeId'],'stageId' => $param['stageId'],'gradeId' => $key,'order'=>$param['order'],'kindId' =>$param['kindId']));
	    			$val['is_active'] = $key == $param['gradeId'] ? 1 : 0;
	    		}
	    	}
    		//2.4 科目
    		$cateArr['subjectArr'] = $cate_subject;
    		if(!empty($param['gradeId'])){#选了年级
    			$filter_subject = $cate_grade[$param['gradeId']]['chirdren'];
    		}else{#没选年级
    			if(!empty($param['stageId'])){#没选年级选了学段
    				$filter_subject = $cate_grade[0]['chirdren_'.$param['stageId']];
    			}else{#没选学段
    				$filter_subject = array();
    			}
    		}
    		foreach($cateArr['subjectArr'] as $key =>&$val){ //key [subjectId]
	    		if($filter_subject && !in_array($key,$filter_subject)){
	    			unset($cateArr['subjectArr'][$key]);
	    		}else{
	    			$val['url'] = $this->getLink(array('typeId' => $param['typeId'],'stageId' => $param['stageId'],'gradeId' => $param['gradeId'],'subjectId' => $key,'order'=>$param['order'],'kindId' =>$param['kindId']));
	    			$val['is_active'] = $key == $param['subjectId'] ? 1 : 0;
	    		}
	    	}
    	}elseif($param['typeId'] == QUALITY_STUDY){
    		//2.5 素质
    		$cateArr['qualityArr'] = $cate_quality;
    		foreach($cateArr['qualityArr'] as $key=> &$val){ //key [qualityId]
	    		$val['url'] = $this->getLink(array('typeId' => $param['typeId'],'qualityId' => $key,'order'=>$param['order'],'kindId' =>$param['kindId']));
	    		$val['is_active'] = $key == $param['qualityId'] ? 1 : 0;
	    	}
    	}
    	//2.6 排序
    	$cateArr['orderArr'] = array(
    		1 => array('name'=>'综合排序','title'=>'按默认推荐' ,'active_name' => '综合排序' ,'class' => ''),
    		2 => array('name'=>'销量','title'=>'按销量从高到低' ,'active_name' => '销量从高到低','class' => ''),
    		3 => array('name'=>'价格 ↓','title'=>'按价格从高到低' ,'active_name' => '价格从高到低','class' => 'down'),
    		4 => array('name'=>'价格 ↑','title'=>'按价格从低到高' ,'active_name' => '价格从低到高', 'class' => 'up'),
    		5 => array('name'=>'时间','title'=>'按最新开课时间' ,'active_name' => '最近开课时间' , 'class' => ''),
    	);
    	$order_param = array(
    		'typeId'	=> $param['typeId'],
    		'stageId'	=> $param['stageId'],
    		'gradeId'	=> $param['gradeId'],
    		'subjectId'	=> $param['subjectId'],
    		'qualityId'	=> $param['qualityId'],
    		'order'		=> $param['order'],
    		'kindId' 	=> $param['kindId'],
    	);
    	foreach ($cateArr['orderArr'] as $key=> &$val){
    		$order_param['order'] = $key;
    		$val['url'] = $this->getLink($order_param);
	    	$val['is_active'] = $key == $param['order'] ? 1 : 0;
    	}
    	return $cateArr;
    }
    
    /**
     * 链接生成
     */ 
    function getLink($param){
    	if(empty($param['typeId'])){exit('类型参数不能为空');}
    	$url = student_url().'list';
    	if($param['typeId'] == SUBJECT_STUDY){
    		$url .= '_t'.$param['typeId'];
    		$url .= !empty($param['stageId']) ? '_st'.$param['stageId'] : '';
    		$url .= !empty($param['gradeId']) ? '_g'.$param['gradeId'] : '';
    		$url .= !empty($param['subjectId']) ? '_su'.$param['subjectId'] : '';
    	}elseif($param['typeId'] == QUALITY_STUDY){
    		$url .= '_t'.$param['typeId'];
    		$url .= !empty($param['qualityId']) ? '_q'.$param['qualityId'] : '';
    	}
    	$url .= !empty($param['order']) && $param['order']>1 ? '_o'.$param['order'] : '';
    	$url .= !empty($param['page']) && $param['page']>1 ? '_p'.$param['page'] : '';
    	$url .= '.html';
    	$tagStr = '?kindId='.$param['kindId'];
    	return $url.$tagStr;
    }
     
    /**
     * 生成seo属性
     * @param 当前类别与排序参数
     * @return 1. SEO标题
     *         2. 面包屑
     */
    function process_SEO($param){
    	$param['qualityId'] = !empty($param['qualityId']) ? $param['qualityId'] : '';
    	$param['stageId'] 	= !empty($param['stageId']) ? $param['stageId'] : '';
    	$param['gradeId'] 	= !empty($param['gradeId']) ? $param['gradeId'] : '';
    	$param['order'] 	= !empty($param['order']) ? $param['order'] : '';
    	$param['subjectId'] = !empty($param['subjectId']) ? $param['subjectId'] : '';
    	#1. 获取配置
    	$cate 			= config_item('cate');
    	$cate_stage 	= config_item('cate_stage');
    	$cate_grade 	= config_item('cate_grade');
    	$cate_subject 	= config_item('cate_subject');
    	$cate_quality 	= config_item('cate_quality');
    	$orderArr = array(
    			1	=> 	'综合推荐的',
    			2	=>	'销量从高到低的',
    			3	=>	'价格从高到低的',
    			4	=>	'价格从低到高的',
    			5	=>	'最新的',
    		);
    	#2. 按参数组合 【标题】+【面包屑】
    	$cateName = $cate[$param['typeId']]['name'];
    	$posLink = $this->getLink(array('typeId' => $param['typeId'],'kindId' =>$param['kindId']));
    	$pos = '<a href="/" title="首页">首页</a> > <a href="'.$posLink.'" title="'.$cateName.'">'.$cateName.'</a>';
    	if($param['typeId'] == SUBJECT_STUDY){
    		$stageName = $gradeName = $orderName = $subjectName = '';
    		if(!empty($param['stageId'])){
    			$stageName 	= $cate_stage[$param['stageId']]['name'];
    			$posParam 	= array('typeId' => $param['typeId'],'stageId' => $param['stageId'],'kindId' =>$param['kindId']);
    			$posLink 	= $this->getLink($posParam);
    			$pos 		.= ' > <a href="'.$posLink.'" title="'.$stageName.'">'.$stageName.'</a>';
    		}
    		if(!empty($param['gradeId'])){
    			$gradeName	= $cate_grade[$param['gradeId']]['name'];
    			$posParam 	= array('typeId' => $param['typeId'],'stageId' => $param['stageId'],'gradeId' => $param['gradeId'],'kindId' =>$param['kindId']);
    			$posLink 	= $this->getLink($posParam);
    			$pos 		.= ' > <a href="'.$posLink.'" title="'.$gradeName.'">'.$gradeName.'</a>';
    		}
    		if(!empty($param['order'])){
    			$orderName	= $orderArr[$param['order']];
    		}
    		if(!empty($param['subjectId'])){
    			$subjectName= $cate_subject[$param['subjectId']]['name'];
    			$posParam 	= array('typeId' => $param['typeId'],'stageId' => $param['stageId'],'gradeId' => $param['gradeId'],'subjectId' => $param['subjectId'],'kindId' =>$param['kindId']);
    			$posLink 	= $this->getLink($posParam);
    			$pos 		.= ' > <a href="'.$posLink.'" title="'.$subjectName.'">'.$subjectName.'</a>';
    		}
    		$title 			= $stageName.$gradeName.$orderName.$subjectName.$cateName.'课程列表_如何快速提升'.$stageName.$gradeName.$subjectName.'应试能力-';
    	}elseif($param['typeId'] == QUALITY_STUDY){
    		$orderName = $qualityName = '';
    		if(!empty($param['order'])){
    			$orderName	= $orderArr[$param['order']];
    		}
    		if(!empty($param['qualityId'])){
    			$qualityName= $cate_quality[$param['qualityId']]['name'];
    			$posParam 	= array('typeId' => $param['typeId'],'qualityId' => $param['qualityId'],'kindId' =>$param['kindId']);
    			$posLink 	= $this->getLink($posParam);
    			$pos 		.= ' > <a href="'.$posLink.'" title="'.$qualityName.'">'.$qualityName.'</a>';
    		}
   			$subtitle 		= $qualityName ? '教你如何成为'.$qualityName.'高手' : '教你如何提高自身素质与修养';
    		$title 			= $orderName.$qualityName.$cateName.'课程列表_'.$subtitle.'-';
    	}
    	$title 				.= !empty($param['page']) ? '第'.$param['page'].'页' : '';
    	return array('title'=>$title,'pos'=>$pos);
    }
     
    /**
     * 分类搜索逻辑
     */
    function search($param){
    	if(empty($param['typeId'])){exit('类型参数不能为空');}
    	$param['typeId'] = !empty($param['typeId']) ? $param['typeId'] : 1;
    	$param['qualityId'] = !empty($param['qualityId']) ? $param['qualityId'] : '';
    	$param['stageId'] = !empty($param['stageId']) ? $param['stageId'] : '';
    	$param['gradeId'] = !empty($param['gradeId']) ? $param['gradeId'] : '';
    	$param['subjectId'] = !empty($param['subjectId']) ? $param['subjectId'] : '';
    	$list = $this->model_list->search($param);
    	#新增，没有就推荐同类
    	$commend = 0;
    	if(!$list){#推荐，就找同类,按销售量推
    		$commend = 1;
    		if($param['typeId']==SUBJECT_STUDY){
    			unset($param['subjectId']);
    			$param['order'] = 2;
    			$list = $this->model_list->search($param);
    		}elseif($param['typeId']==QUALITY_STUDY){
    			unset($param['qualityId']);
    			$param['order'] = 2;
    			$list = $this->model_list->search($param);
    		}
    	}
		$param['counter'] = 1;
		$counter = $this->model_list->search($param);
    	
    	#组合参数：小图标，时间格式，学习人数，课程图片，老师图片
    	if($list) foreach ($list as &$val){
    		$val['icon'] = array();
    		//今日新课： 今天开卖
//    		if(date('Y-m-d',$val['sell_begin_time']) == date('Y-m-d',time())){
//    			$val['icon'][] 	= array('name'=>'今日新课','class'=>'mark6');
//    		}
//			//限时抢购： 距离销售结束时间小于5天
//			if((time() > $val['sell_end_time']) && ((time() - $val['sell_end_time']) < 86400*5)){
//				$val['icon'][] 	= array('name'=>'限时抢购','class'=>'mark3');
//			}
//			//免费试听： 0元
//			if($val['sale_price'] == 0){
//				$val['icon'][] 	= array('name'=>'免费试听','class'=>'mark4');
//			}
//			//疯狂热卖： 购买人数超过150
//			if(($val['bought_count'] + $val['extra_bought_count']) >150){
//				$val['icon'][] 	= array('name'=>'疯狂热卖','class'=>'mark5');
//			}
			//课程类型
			if($val['course_type']){
				$course_type_Arr = config_item('course_type');
				$val['icon'][] 	= array('name'=>$course_type_Arr[$val['course_type']],'class'=>'mark7');
			}
			//教材版本
			if($val['material_version'] && $val['material_version']!=1){
				$material_version_Arr = config_item('material_version');
				$val['icon'][] 	= array('name'=>$material_version_Arr[$val['material_version']],'class'=>'mark8');
			}
    		//日期
    		$val['start_date'] 	= date('m月d日 H:i',$val['start_time']);
    		//学习人数
    		$val['study_count'] = $val['bought_count'] + $val['extra_bought_count'];
    		//课程图片
	       	$val['img_url'] 	= empty($val['img']) ? get_img_url(HOME_IMG_DEFAULT,'course_s4') : get_img_url($val['img'],'course_s4');
    		//用户头像
    		$val['avater_url'] 	= $this->get_user_avater($val['teacher_id']);
    		//subtitle
    		$val['subtitle']	= (mb_substr($val['subtitle'],0,50,'UTF-8')).(strlen($val['subtitle'])>50 ? '...' : '');
    		//teacher_intro
    		$val['teacher_intro']= htmlspecialchars_decode($val['teacher_intro']);
    		//价格整形
    		$val['price']		= intval($val['price']);
    		$val['sale_price']	= intval($val['sale_price']);
    	}
    	if($commend){
    		return array('data' => array() ,'total' =>$counter[0]['total'], 'commend_data' => $list ,'is_commend' => $commend);
    	}else{
    		return array('data' => $list , 'total' =>$counter[0]['total'], 'commend_data' => array(),'is_commend' => $commend);
    	}
    }
    
    /**
     * 搜索推荐-猜您喜欢
     */
    public function search_suggest($param){
    	if(empty($param['typeId'])){exit('类型参数不能为空');}
    	$param = array(
    		'typeId' 	=> ($param['typeId'] == SUBJECT_STUDY ? QUALITY_STUDY : SUBJECT_STUDY),
    		'order'		=> 5,
    		'page'		=> 1,
    		'num'		=> LIST_SUGGEST_NUM,
    	);
    	$suggest_list = $this->model_list->search_suggest($param);
    	#组合参数：学习人数，课程图片
    	if($suggest_list) foreach ($suggest_list as &$val){
    		//学习人数
    		$val['study_count'] = $val['bought_count'] + $val['extra_bought_count'];
    		//课程图片
	       	$val['img_url'] 	= empty($val['img']) ? get_img_url(HOME_IMG_DEFAULT,'course_s5') : get_img_url($val['img'],'course_s5');
    		//价格整形
    		$val['price']		= intval($val['price']);
    		$val['sale_price']	= intval($val['sale_price']);
    	}
    	return array('data' => $suggest_list);
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
    			$avatar = get_img_url($array_return['avatar'],'avatar_s5');;
    		} else {
    			if ($array_return['teach_priv'] == 1){
    				$avatar = get_img_url(DEFAULT_TEACHER_AVATER,'avatar_s5');
    			} else{
    				$avatar = get_img_url(DEFAULT_STUDENT_AVATER,'avatar_s5');
    			}
    		}
    	}

    	return $avatar;
    }
    
    /**
   	 * 列表分页
   	 * @param: 【page/当前页】【total/总条数】【num/每页条数】
   	 */
    function getPageBar($config)
   	{
   		$config['page'] = $config['page']>0 ? $config['page'] : 1;
    	$config['pages'] = ceil($config['total']/$config['num']);
    	$config['base_link'] = $config['base_link'];
    	if($config['pages']<=1){
    		return '';
    	}
    	//新增往期标签属性,分页会附带query_string部分,参数为get接收
    	$tagStr ='?kindId='.$param['kindId'];
    	$pageBar = '<ul>';
    	$pageBar .= $config['page']>2  ? '<li class="prev"><a href="'.str_replace('.html','_p'.($config['page']-1).'.html',$config['base_link']).$tagStr.'">上一页</a></li>' : '';
    	$pageBar .= $config['page']>1 ? '<li><a href="'.$config['base_link'].$tagStr.'">1</a></li>' : '';
    	$pageBar .= $config['page']>4 ? '<li class="more"><a>...</a></li>' : '';
    	
    	for ($i=1;$i<=$config['pages'];$i++){
    		$li = '';
    		if($i==($config['page']-2) || $i==($config['page']-1) || $i==($config['page']+2) || $i==($config['page']+1)){
    			$li = ($i!=1 && $i!=$config['pages']) ? '<li><a href="'.str_replace('.html','_p'.$i.'.html',$config['base_link']).$tagStr.'">'.$i.'</a></li>' : '';
    		}elseif($i==$config['page']){
    			$li = '<li class="active"><a>'.$i.'</a></li>';
    		}
    		$pageBar .=$li;
    	}
    	$pageBar .= ($config['page']<$config['pages']-3) && $config['page']>0 ? '<li class="more"><a>...</a></li>' : '';
    	$pageBar .= $config['page']<$config['pages'] ? '<li><a href="'.str_replace('.html','_p'.$config['pages'].'.html',$config['base_link']).$tagStr.'">'.$config['pages'].'</a></li>' : '';
    	$pageBar .= $config['page']>0 && $config['page']<($config['pages']-1) ? '<li class="next"><a href="'.str_replace('.html','_p'.($config['page']+1).'.html',$config['base_link']).$tagStr.'">下一页</a></li>' : '';
    	$pageBar .= '</ul>';
    	return $pageBar;
   	}
   	
   	/**
     * 最近浏览（读cookie）
     */
    public function read_recent_view_data()
    {
    	if (empty($_COOKIE['recent_view']))
    	{
    		return array();
    	}
    	$str_value = $_COOKIE['recent_view'];
    	$array_round_ids = explode(',',$_COOKIE['recent_view']);
    	$this->load->model('model/common/model_redis', 'redis');
    	$this->redis->connect('recent_view_data');
    	$array_list = array();
    	foreach ($array_round_ids as $k=>$v)
    	{
    		$array_round = $this->cache->redis->get($v);
			if ($array_round)
			{
				$array_list[] = json_decode($array_round,true);
			}
    	}
		return $array_list;
    }
}