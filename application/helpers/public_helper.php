<?php

/**
 * p()
 * 代码调试
 * @return
 */
function p()
{
    $argc = func_get_args();
    echo '<pre>';
    foreach ($argc as $var) {
        print_r($var);
    }
    echo '</pre>';
    return ;
}

function o($mix_param,$bool_flag=false){
    header("Content-type: text/html; charset=utf-8");
    if($bool_flag==true){
        echo "<pre>";
    }
    var_dump($mix_param);
    if($bool_flag==true){
        exit;
    }
}

/**
 * d()
 * 代码调试
 * @return
 */
function d()
{
    $argc = func_get_args();
    foreach ($argc as $var) {
        var_dump($var);
    }
    return ;
}

/**
 * 返回二维数组中每个元素数组中你想要的字段
 */
function return_your_values($array,$field='id'){
    return $array[$field];
}


/**
 * 字符串截取函数，支持中文
 *
 * @param string $str
 * @param int $start
 * @param int $length
 * @param string $charset
 * @param bool $suffix
 * @return string
 */
function csubstr($str, $start=0, $length, $charset="utf-8", $suffix='')
{

   if(function_exists("mb_substr"))
   {

       if(mb_strlen($str, $charset) <= $length) return $str;

       $slice = mb_substr($str, $start, $length, $charset);

   }
   else
   {

       $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";

       $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";

       $re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";

       $re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

       preg_match_all($re[$charset], $str, $match);

       if(count($match[0]) <= $length) return $str;

       $slice = join("",array_slice($match[0], $start, $length));

   }

   if(!empty($suffix)) return $slice.$suffix;

   return $slice;

}

function cn_strlen($string = NULL){
    preg_match_all('/./us', $string, $match);
    return count($match[0]);
}

/**
 * 获取授课方式的中文标示
 *
 * @param int $teach_type
 * @return string
 */
function teach_type_cn($teach_type)
{
    $teach_type = max((int)$teach_type, 0);
    switch ($teach_type) {
        case 0:
            $teach_type_cn  =   '大课堂';
            break;
        default:
            $teach_type_cn  =   '1对' . $teach_type;
            break;
    }
    return $teach_type_cn;
}

function teach_type_en($teach_type)
{
    switch ($teach_type) {
        case 1:
            $teach_type_cn  =  '1 v 1';
            break;
        case 2:
            $teach_type_cn  =   '1 v 2';
            break;
        case 0:
        default:
            $teach_type_cn  =   '1 v N';
            break;
    }
    return $teach_type_cn;
}


function class_status_cn($status)
{
    switch ($status) {
        case CLASS_STATUS_INIT:
            return '未开始';
        case CLASS_STATUS_TEACHING:
            return '教学中';
        case CLASS_STATUS_FINISH:
            return '已结束';
        case CLASS_STATUS_PAYMENT:
            return '已完成';
        case CLASS_STATUS_CANCEL:
            return '已取消';
    }
}

/**
 * 返回老师类型中文
 *
 * @param int $teacher_type
 * @return string
 */



function friendly_time($time)
{
    $date   = date('Y-m-d', $time);//日期
    $week   = date('w', $time);
    $time   = date("H:i:s", $time);

    switch ($week) {
        case 1:
            $week   =   '星期一';
            break;
        case 2:
            $week   =   '星期二';
            break;
        case 3:
            $week   =   '星期三';
            break;
        case 4:
            $week   =   '星期四';
            break;
        case 5:
            $week   =   '星期五';
            break;
        case 6:
            $week   =   '星期六';
            break;
        case 0:
            $week   =   '星期日';
            break;
        default:
            break;
    }
    $friendly_time  =   "{$date} {$week} {$time}";
    return $friendly_time;
}
//转换成中文的星期
function week_zh($time)
{

	$week   = date('w', $time);


	switch ($week) {
		case 1:
			$week   =   '星期一';
			break;
		case 2:
			$week   =   '星期二';
			break;
		case 3:
			$week   =   '星期三';
			break;
		case 4:
			$week   =   '星期四';
			break;
		case 5:
			$week   =   '星期五';
			break;
		case 6:
			$week   =   '星期六';
			break;
		case 0:
			$week   =   '星期日';
			break;
		default:
			break;
	}
	return $week;
}

function format_date($time_stamp = TIME_STAMP,  $format='Y-m-d H:i:s')
{
    $time_stamp = (int)$time_stamp;
    if($time_stamp < 1)
    {
        return '-- --';
    }
    return date($format, $time_stamp);
}

function sms_status_cn($status){
    switch ($status) {
        case 0:
            return '未发送';
        case 1:
            return '发送成功';
        case 2:
            return '发送失败';
        default:
            return '未知';
    }
}

function order_status_cn($status)
{
    $cn_name = '';
    switch ($status) {
        case ORDER_STATUS_DEBT:
            $cn_name = '支付失败';
            break;
        case ORDER_STATUS_SIGN:
            $cn_name = '签名错误';
            break;
        case ORDER_STATUS_FAIL:
            $cn_name = '金额校验错误';
            break;
        case ORDER_STATUS_INIT:
            $cn_name = '待付款';
            break;
        case ORDER_STATUS_SUCC:
            $cn_name = '已付款';
            break;
        case ORDER_STATUS_FINISH:
            $cn_name = '交易成功';
            break;
        case ORDER_STATUS_CANCEL:
            $cn_name = '交易关闭';
            break;
        case ORDER_STATUS_REFUND:
            $cn_name = '已退款';
            break;
    }
    return $cn_name;
}



//获取那好渠道0请选择;1户外广告;2百度;3朋友推荐;4期刊杂志;5微博;6其他
function get_waijiao_source($source){
    switch ($source) {
        case 0:
            return '无';
        case 1:
            return '户外广告';
        case 2:
            return '百度';
        case 3:
            return '朋友推荐';
        case 4:
            return '期刊杂志';
        case 5:
            return '微博';
        case 6:
            return '其他';
        default:
            break;
    }
}

/**
 * 课件上传路径生成
 *
 * @param int $id
 * @return string
 */
function create_upload_url($id){
    return "./uploads/" .ceil($id / 1000) . '/';
}

/**
 * 讲义上传pdf路径生成
 *
 * @param int $id
 * @return string
 */
function create_handout_upload_url($cw_id){
    return "./uploads/" . "handout_pdf/" . ceil($cw_id / 1000) . '/';
}

// 获得讲义文件路径
function get_handout_url($cw_id)
{
	return "./uploads/" . "handout_pdf/" . ceil($cw_id / 1000) . '/' . $cw_id . '.pdf';
}

/**
 * 学生头像
 */
function student_avatar_urls($student_id){
    $dir1 = ceil($student_id / 10000.0);
    $dir2 = ceil($student_id % 10000 / 1000.0);
    return "./uploads/students/"."{$dir1}/{$dir2}/".$student_id;
}

/**
 * 判断id在数组中是否已经存在
 *
 */
function has_array_id($id, $array){
    foreach ($array as $key => $value) {
        if($id == $value['id']){
            return $key;
        }
    }
    return false;
}

/**
 * 获取二位数组中的一列
 */
if ( ! function_exists('array_column'))
{
    function array_column($input, $column_key = null, $index_key = null)
    {
        $res = array();
        foreach ($input as $item) {
            $column_val = isset($item[$column_key]) ? $item[$column_key] : null;
            if ($index_key == null || !isset($item[$index_key])) {
                $res[] = $column_val;
            } else {
                $res[$item[$index_key]] = $column_val;
            }
        }
        return $res;
    }
}

/**
 * 递归创建目录
 */
if (!function_exists('recursiveMkdirDirectory')) {
    function recursiveMkdirDirectory($dir, $mode = 0777)
    {
        if (!is_dir($dir)) {
            if (!recursiveMkdirDirectory(dirname($dir))) {
                return false;
            }
            if (!mkdir($dir, 0777)) {
                return false;
            }
        }
        return true;
    }
}

/**
 * 远程curl获取接口内容
 */
if (!function_exists('getHttpResponse')) {
    function getHttpResponse($url, $is_post=false, $para=array()) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        if($is_post){
            curl_setopt($curl,CURLOPT_POST,true); // post传输数据
            curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
        }
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
        return $responseText;
    }
}

//if (!function_exists('getPhoneArea')) {
//    function getPhoneArea($phone) {
//        $str_url = 'http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=';
//        $curl = curl_init($str_url.$phone);
//        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
//        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
//        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
//        $responseText = curl_exec($curl);
//        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
//        curl_close($curl);
//        return $responseText;
//    }
//}

/**
 * 根据当前域名生成静态文件的url
 * @param $str_url
 * @return string
 * @author yanrui@tizi.com
 */
function static_url($str_url){
	$str_static_url = config_item('static_url').config_item('static_version').$str_url . '?v=' . config_item('version');
	return $str_static_url;
}

/**
 * 创建密码
 * @param $str_salt
 * @param string $str_password
 * @return string
 * @author yanrui@tizi.com
 */
function create_password($str_salt,$str_password = NH_INIT_PASSWORD){
    return sha1($str_salt.sha1($str_password));
}

/**
 * 前台用的创建密码
 * @param string $str_salt
 * @param string $sha1_password
 * @return string
 */
function create_sha1_password($str_salt, $sha1_password){
    return sha1($str_salt . $sha1_password);
}

/**
 * @param $password
 * @param $salt
 * @param $sys_password
 * @return bool
 */
function check_password($salt, $password, $sys_password)
{
    return create_password($salt, $password) === $sys_password;
}


/**
 * @param $salt
 * @param $sha1_password
 * @param $sys_password
 * @return bool
 */
function check_sha1_password($salt, $sha1_password, $sys_password)
{
    return sha1($salt.$sha1_password) === $sys_password;
}

/**
 * 验证手机号
 * @param $str_mobile
 * @return bool
 * @author yanrui@tizi.com
 */
function is_mobile($str_mobile)
{
    $pattern = '/^(13[0-9]{1}[0-9]{8}|15[0-9]{1}[0-9]{8}|18[0-9]{1}[0-9]{8}|14[0-9]{9}|17[078][0-9]{8})$/';
    return (bool)preg_match($pattern, $str_mobile);
}

/**
 * 验证email
 * @param string $str_email
 * @return bool
 * @author yanrui@tizi.com
 */
function is_email($str_email)
{
    return preg_match("/^[a-z0-9]{1}[-_\.|a-z0-9]{0,19}@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{0,3}([\.][a-z]{1,3})?$/i", $str_email) ? true : false;
}

/**
 * 获取course的封面图
 * @param $str_img_url
 * @param $str_size
 * @return string
 * @author yanrui@tizi.com
 */
//function get_course_img_by_size($str_img_url, $str_size){
//    $str_return = NH_QINIU_URL.$str_img_url;
//    if(in_array($str_size,array('large','general','small','index','live','buy_before_top_big','buy_before_right_recommend','recent_view','index_avatar'))){
////        $str_img_url .= '?imageView/2/w/';
//        $str_img_url .= '?imageView/1/w/';
//        if($str_size=='large'){
//            $str_img_url .= NH_COURSE_IMG_LARGE_WIDTH.'/h/'.NH_COURSE_IMG_LARGE_HEIGHT;
//        }else if($str_size=='general'){
//            $str_img_url .= NH_COURSE_IMG_GENERAL_WIDTH.'/h/'.NH_COURSE_IMG_GENERAL_HEIGHT;
//        }else if($str_size=='small'){
//            $str_img_url .= NH_COURSE_IMG_SMALL_WIDTH.'/h/'.NH_COURSE_IMG_SMALL_HEIGHT;
//        }else if($str_size=='index'){
//            $str_img_url .= NH_COURSE_IMG_INDEX_WIDTH.'/h/'.NH_COURSE_IMG_INDEX_HEIGHT;
//        }else if($str_size=='live'){
//            $str_img_url .= NH_COURSE_IMG_LIVE_WIDTH.'/h/'.NH_COURSE_IMG_LIVE_HEIGHT;
//        }else if($str_size=='buy_before_top_big'){
//        	$str_img_url .= NH_BUY_BEFORE_TOP_BIG_IMG_WIDTH.'/h/'.NH_BUY_BEFORE_TOP_BIG_IMG_HEIGHT;
//        }else if($str_size=='buy_before_right_recommend'){
//        	$str_img_url .= NH_BUY_BEFORE_RIGHT_RECOMMEND_IMG_WIDTH.'/h/'.NH_BUY_BEFORE_RIGHT_RECOMMEND_IMG_HEIGHT;
//        }else if($str_size=='recent_view'){
//        	$str_img_url .= NH_RECENT_VIEW_IMG_WIDTH.'/h/'.NH_RECENT_VIEW_IMG_HEIGHT;
//        }else if($str_size=='index_avatar'){
//            $str_img_url .= NU_USER_AVATAR_EDGE.'/h/'.NU_USER_AVATAR_EDGE;
//        }else if($str_size=='suggest'){
//            $str_img_url .= NH_COURSE_IMG_SUGGEST_WIDTH.'/h/'.NH_COURSE_IMG_SUGGEST_HEIGHT;
//        }
//
//        $str_return = NH_QINIU_URL.$str_img_url;
//    }
//    return $str_return;
//}


/**
 * 获取七牛图片地址
 * @param $str_img_uri
 * @param $str_size
 * @return string
 * @author yanrui@tizi.com
 */
function get_img_url($str_img_uri, $str_size){
    $str_return = '';
    if($str_img_uri){
        $int_server = nahao_hash($str_img_uri,4);
        $str_img_url = str_replace('1',$int_server,NH_QINIU_URL).$str_img_uri;
        $arr_allow_size = array(
            'course_s1' => 'c.'.NH_IMG_SIZE_COURSE_W1.'.'.NH_IMG_SIZE_COURSE_H1,//230x147 首页直播课封面
            'course_s2' => 'c.'.NH_IMG_SIZE_COURSE_W2.'.'.NH_IMG_SIZE_COURSE_H2,//367x235 首页课程列表封面
            'course_s3' => 'c.'.NH_IMG_SIZE_COURSE_W3.'.'.NH_IMG_SIZE_COURSE_H3,//80x51 全局浏览记录课程封面,后台课程和轮列表封面
            'course_s4' => 'c.'.NH_IMG_SIZE_COURSE_W4.'.'.NH_IMG_SIZE_COURSE_H4,//240x154 列表页课程列表封面
            'course_s5' => 'c.'.NH_IMG_SIZE_COURSE_W5.'.'.NH_IMG_SIZE_COURSE_H5,//198x127 列表页猜你喜欢课程封面
            'course_s6' => 'c.'.NH_IMG_SIZE_COURSE_W6.'.'.NH_IMG_SIZE_COURSE_H6,//440x280 购买前课程封面
            'course_s7' => 'c.'.NH_IMG_SIZE_COURSE_W7.'.'.NH_IMG_SIZE_COURSE_H7,//200x127 购买前推荐课程封面
            'course_s8' => 'c.'.NH_IMG_SIZE_COURSE_W8.'.'.NH_IMG_SIZE_COURSE_H8,//130x82 我的课程页面列表课程封面
            'course_s9' => 'c.'.NH_IMG_SIZE_COURSE_W9.'.'.NH_IMG_SIZE_COURSE_H9,//50x50 我的订单页面列表课程封面
            'course_s10' => 'c.'.NH_IMG_SIZE_COURSE_W10.'.'.NH_IMG_SIZE_COURSE_H10,//238x152 我的课程页面 最新课程和热报课程

            'avatar_s1' => 'a.'.NH_IMG_SIZE_USER_AVATAR_S1.'.'.NH_IMG_SIZE_USER_AVATAR_S1,//130x130 教师修改头像前预览
            'avatar_s2' => 'a.'.NH_IMG_SIZE_USER_AVATAR_S2.'.'.NH_IMG_SIZE_USER_AVATAR_S2,//100x100 购买前教师团队头像
            'avatar_s3' => 'a.'.NH_IMG_SIZE_USER_AVATAR_S3.'.'.NH_IMG_SIZE_USER_AVATAR_S3,//70x70 购买后教师团队头像
            'avatar_s4' => 'a.'.NH_IMG_SIZE_USER_AVATAR_S4.'.'.NH_IMG_SIZE_USER_AVATAR_S4,//50x50 首页登录后，个人中心左侧，购买前评价
            'avatar_s5' => 'a.'.NH_IMG_SIZE_USER_AVATAR_S5.'.'.NH_IMG_SIZE_USER_AVATAR_S5,//45x45 首页课程列表封面翻转后教师头像，列表页课程列表封面翻转后教师头像
            'avatar_s6' => 'a.'.NH_IMG_SIZE_USER_AVATAR_S6.'.'.NH_IMG_SIZE_USER_AVATAR_S6,//35x35 购买课程后页面 右侧头像

            'profile_s1' => 'p.'.NH_IMG_SIZE_USER_PROFILE_W1.'.'.NH_IMG_SIZE_USER_PROFILE_H1,//300x225 教师资格证书
        );
        $str_return = array_key_exists($str_size,$arr_allow_size) ? $str_img_url.'/'.$arr_allow_size[$str_size].'.jpg' : $str_img_url;
    }
    return $str_return;
}

/**
 * general signature for meeting
 * @return string
 * @author yanrui@tizi.com
 */
function get_meeting_signature(){
    return md5(NH_MEETING_ACCESS_KEY.':'.TIME_STAMP.':'.NH_MEETING_SECRET_KEY);
}

/**
 * general param for meeting
 * @return array
 * @author yanrui@tizi.com
 */
function get_meeting_param(){
    return $arr_param = array(
        'nonce' => TIME_STAMP,
        'app_key' => NH_MEETING_ACCESS_KEY,
        'signature' => get_meeting_signature()
    );
}

/**
 * common curl method
 * @param $str_url
 * @param $arr_param
 * @param $str_type
 * @return mixed
 * @author yanrui@tizi.com
 */
function nh_curl($str_url,$arr_param,$str_type='post') {
    $obj_curl = curl_init();
    if($str_type=='post'){
        curl_setopt($obj_curl, CURLOPT_POST, 1);
        curl_setopt($obj_curl, CURLOPT_POSTFIELDS, http_build_query($arr_param));
    }elseif($str_type=='delete'){
        curl_setopt($obj_curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        $str_url .= '?'.http_build_query($arr_param);
    }else{
        $str_url .= '?'.http_build_query($arr_param);
    }
//    o($str_url);
    curl_setopt($obj_curl,CURLOPT_URL,$str_url);
//    curl_setopt($obj_curl, CURLOPT_HEADER,array("Content-length: 99999") ); // 设置header 过滤HTTP头
    curl_setopt($obj_curl, CURLOPT_HEADER,0); // 设置header 过滤HTTP头
    curl_setopt($obj_curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($obj_curl, CURLOPT_TIMEOUT, 10);
    $str_response = curl_exec($obj_curl);
//    echo http_build_query($arr_param);
//    var_dump(curl_getinfo($obj_curl));exit;
//    var_dump($str_response);
    //var_dump( curl_error($obj_curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($obj_curl);
    return $str_response;
}

/**
 * get token to operation about meeting
 * @param $int_meeting_id
 * @param $int_user_type
 * @return string
 * @author yanrui@tizi.com
 */
function get_meeting_token($int_meeting_id = 0,$int_user_type = NH_MEETING_TYPE_SUPER_ADMIN){
    $str_token = '';
    if($int_meeting_id >= 0 AND in_array($int_user_type,array_keys(config_item('nh_meeting_type')))){
        $arr_param = array(
            'type' => $int_user_type,
        );
        if($int_meeting_id > 0){
            $arr_param['meeting_id'] = $int_meeting_id;
            $arr_param['params'] = json_encode(array('UserName' => 'nahao'));
        }
        $arr_meeting_param = get_meeting_param();
        $arr_param = array_merge($arr_param,$arr_meeting_param);
        $str_url = NH_MEETING_URL.'api/tokens/';
//        o($str_url);
//        o($arr_param,true   );
        $str_response = nh_curl($str_url,$arr_param);
//        o($str_response);
        if($str_response){
            $arr_response = json_decode($str_response,true);
            $str_token = ($arr_response AND isset($arr_response['token'])) ? $arr_response['token'] : '';
        }
    }
    return $str_token;
}

/**
 * general_classroom_id
 * @param $arr_param
 * @return int
 * @author yanrui@tizi.com
 */
function general_classroom_id($arr_param){
    $int_return = 0;
    if($arr_param){
        $str_url = NH_MEETING_URL.'api/meetings/';
//        echo $str_url;
        $arr_meeting_param = get_meeting_param();
        $arr_param = array_merge($arr_param,$arr_meeting_param);
        $str_response = nh_curl($str_url,$arr_param);
        //log
        if($str_response){
            $arr_response = json_decode($str_response,true);
//            o($str_response,true);
            $int_return = ($arr_response AND isset($arr_response['id'])) ? $arr_response['id'] : 0;
        }
    }
    return $int_return;
}

/**
 * set_courseware_to_classroom
 * @param $int_classroom_id
 * @param $int_courseware_id
 * @return bool
 * @author yanrui@tizi.com
 */
function set_courseware_to_classroom($int_classroom_id,$int_courseware_id){
    $bool_flag = false;
    if($int_classroom_id > 0 AND $int_courseware_id > 0){
        $str_url = NH_MEETING_URL.'api/meetings/'.$int_classroom_id.'/assoc_file/';
        $arr_meeting_param = get_meeting_param();
        $arr_param['file_id'] = $int_courseware_id;
        $arr_param = array_merge($arr_param,$arr_meeting_param);
        $str_response = nh_curl($str_url,$arr_param);
//        o($str_url);
//        o($arr_param);
//        o($str_response);
        //TODO log
        if($str_response){
            $arr_response = json_decode($str_response,true);
//            o($arr_response,true);
            $bool_flag = ($arr_response AND isset($arr_response['status'])) ? $arr_response['status'] : false;
        }
    }
    return $bool_flag;
}

/**
 * enter_classroom
 * @param $int_meeting_id
 * @param $int_user_type
 * @return string
 * @author yanrui@tizi.com

function enter_classroom($int_meeting_id,$int_user_type){
    $str_enter_classroom_url = '';
    if($int_meeting_id > 0 AND in_array($int_user_type,array_keys(config_item('nh_meeting_type')))){
        $str_token = get_meeting_token($int_meeting_id,$int_user_type);
//        o($str_token,true);
        $str_enter_classroom_url = $str_token ? NH_MEETING_ENTER_URL.$str_token : '';
    }
    return $str_enter_classroom_url;
}
*/

function get_name_length($str, $str_encoding='utf-8', $debug=false)
{
    $str_encoding = strtolower($str_encoding);
    if($str_encoding != 'utf-8')
    {
        $str = mb_convert_encoding($str, 'utf-8', $str_encoding);
    }

    $total_len = 0;
    $str = trim($str);
    //匹配中文
    if(preg_match_all("/[\x{4e00}-\x{9fa5}]/u",$str, $matches))
    {
        if($debug) print_r($matches);
        $total_len = $total_len + count($matches[0])*2;
    }

    //匹配数字字母
    if(preg_match_all("/[\w\s]/", $str, $matches))
    {
        if($debug) print_r($matches);
        $total_len = $total_len + count($matches[0]);
    }

    return $total_len;
}


/**
 * @param $str, 待检查的字符串
 * @param int $min_len，允许的最小长度，默认4个字符。注意一个汉字算2个字符
 * @param $max_len，允许的最大长度，默认16个字符
 * @param string $str_encoding， 带检查的字符串编码，默认utf-8
 */
function check_name_length($str, $min_len=4, $max_len=25, $str_encoding='utf-8', $debug=false)
{
    $total_len = get_name_length($str, $str_encoding, $debug);

    if($debug) echo "total_len: $total_len<br>";
    if($total_len > $max_len || $total_len < $min_len )
    {
        return false;
    }
    return true;
}

/**
 * test and show classroom request
 * @param $str_uri
 * @param $arr_param
 * @author yanrui@tizi.com
 */
function test_nahao_classroom($str_uri,$arr_param=array()){
    $str_url = NH_MEETING_URL.$str_uri;
    $arr_meeting_param = get_meeting_param();
    $arr_param = $arr_param ? array_merge($arr_param,$arr_meeting_param) : $arr_meeting_param;
    $str_response = nh_curl($str_url,$arr_param,'get');
    o($str_url);
    o($arr_param);
    o(json_decode($str_response));
    exit;
}

function get_courseware_info($int_courseware_id){
//    "id": 98,
//    "url": "http://classroom.oa.tizi.com/api/files/98/",
//    "fileobj": "98/\u767e\u5ea6\uff1a2013\u5728\u7ebf\u6559\u80b2\u7814\u7a76\u62a5\u544a.pdf",
//    "filesize": 1678091,
//    "filetype": "pdf",
//    "filename": "\u767e\u5ea6\uff1a2013\u5728\u7ebf\u6559\u80b2\u7814\u7a76\u62a5\u544a.pdf",
//    "docname": "\u767e\u5ea6\uff1a2013\u5728\u7ebf\u6559\u80b2\u7814\u7a76\u62a5\u544a",
//    "swfpath": "http://classroom.oa.tizi.com/media/98/swf/xxxx.swf",
//    "pagenum": 37,
//    "params": "{}",
//    "created_at": "2014-06-18T09:12:24Z",
//    "updated_at": "2014-06-18T09:12:41Z",
//    "download_url": "http://classroom.oa.tizi.com/media/98/%E7%99%BE%E5%BA%A6%EF%BC%9A2013%E5%9C%A8%E7%BA%BF%E6%95%99%E8%82%B2%E7%A0%94%E7%A9%B6%E6%8A%A5%E5%91%8A.pdf",
//    "preview_url": "http://classroom.oa.tizi.com/meeting/preview/98",
//    "display_filesize": "1.6 M",
//    "display_created_at": "2014-06-18 17:12",
//    "display_updated_at": "2014-06-18 17:12"
    $arr_response = array();
    $str_url = NH_MEETING_URL.'api/files/'.$int_courseware_id.'/';
//    $str_url = 'http://classapi.tizi.com/api/files/'.$int_courseware_id.'/';

    $arr_meeting_param = get_meeting_param();
    $str_response = nh_curl($str_url,$arr_meeting_param,'get');
    if($str_response){
        $arr_response = json_decode($str_response,true);
        $arr_response = isset($arr_response['id']) ? $arr_response : array();
    }
    return $arr_response;
}

function get_courseware_status($int_courseware_id){
    $arr_response = array();
    $str_url = NH_MEETING_URL.'api/files/'.$int_courseware_id.'/status/';
    $arr_meeting_param = get_meeting_param();
    $str_response = nh_curl($str_url,$arr_meeting_param,'get');
    if($str_response){
        $arr_response = json_decode($str_response,true);
//        o($arr_response,true);
        $arr_response = isset($arr_response['status']) ? $arr_response : array();
    }
    return $arr_response;
}

function reload_courseware($int_classroom_id){
    $arr_response = array();
    $str_url = NH_MEETING_URL.'api/meetings/'.$int_classroom_id.'/reload/';
    $arr_meeting_param = get_meeting_param();
    $str_response = nh_curl($str_url,$arr_meeting_param,'post');
    if($str_response){
        $arr_response = json_decode($str_response,true);
//        o($arr_response,true);
        $arr_response = isset($arr_response['status']) ? $arr_response : array();
    }
    return $arr_response;
}

function get_coursewares_by_classroom_id($int_classroom_id){
    $str_url = NH_MEETING_URL.'api/meetings/'.$int_classroom_id.'/files/';
    $arr_meeting_param = get_meeting_param();
    $str_response = nh_curl($str_url,$arr_meeting_param,'get');
    if($str_response){
        $arr_response = json_decode($str_response,true);
        $arr_ids = array();
        if(isset($arr_response['count']) AND $arr_response['count'] > 0){
            foreach($arr_response['results'] as $files){
                $arr_ids[] = $files['id'];
            }
        }
        $arr_response = $arr_ids;
    }
    return $arr_response;
}

function delete_courseware_by_classroom_id($int_classroom_id,$int_courseware_id){
    $str_url = NH_MEETING_URL.'api/meetings/'.$int_classroom_id.'/files/'.$int_courseware_id.'/';
    $arr_meeting_param = get_meeting_param();
    $str_response = nh_curl($str_url,$arr_meeting_param,'delete');
//    o($str_response,true);
    if($str_response){
        $arr_response = json_decode($str_response,true);
//        o($arr_response,true);
    }
    $arr_response = isset($arr_response['status']) ? $arr_response : array();
}

/**
 * 判断是否有权限
 * @param string $ctrl
 * @param string $act
 * @return bool
 */
function pass($ctrl = '', $act = '')
{
	static $permissions = null;
	if ($permissions === null) {
		$CI =& get_instance();
		$permissions = false;
		if ($CI->userinfo) {
			$user = $CI->userinfo;
			$admin_group = T(TABLE_ADMIN_PERMISSION_RELATION)->getOneRowByColumn('admin_id',$user['id']);
			if ((!empty($admin_group)&&($admin_group['group_id'] == 6))||$user['id'] == 1) {
				$permissions = true;
			} else{
				$data  = T(TABLE_ADMIN_PERMISSION_RELATION . ' AS apr')->find(array('apr.admin_id' => $user['id']))
				->join(TABLE_GROUP_PERMISSION_RELATION . ' AS gpr','gpr.group_id = apr.group_id')
				->join(TABLE_PERMISSION . ' AS p', 'gpr.permission_id = p.id')->select('p.controller, p.action')->get()->result_array();
// 				print_r($data);
				$res = array();
				foreach($data as $item) {
					$res[strtolower($item['controller'])][strtolower($item['action'])] = true;
				}
				$permissions = $res;
			}
		}
	}
	if (is_bool($permissions)) {
		return $permissions;
	}
	return isset($permissions[strtolower($ctrl)][strtolower($act)]);
}


/**
 * 下载课件PDF文件
 * @param  $url
 * @param  $file_name
 */
function download($url,$file_name)
{
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $content=curl_exec($ch);
    if(curl_errno($ch))
    {
        echo curl_error($ch);
        curl_close($ch);
    } else {
        curl_close($ch);
        //提取文件名和文件类型
        $array_type=explode('.',$url);
        $last_index=count($array_type)-1;
        $file_type=$array_type[$last_index];
        //获得文件大小
        $file_size=strlen($content);

        //通知浏览器下载文件
        if (preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])) {

            $attachmentHeader = 'Content-Disposition: attachment; filename="'.$file_name.'"';
        } else if (preg_match("/Firefox/", $_SERVER["HTTP_USER_AGENT"])) {
            $attachmentHeader = 'Content-Disposition: attachment; filename*="utf8\'\'' . $file_name. '"' ;
        } else {
            $attachmentHeader = 'Content-Disposition: attachment; filename="'.$file_name.'"';
        }
        header('Content-Encoding: none');
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/".$file_type);

        header("Content-Transfer-Encoding: binary");
        header($attachmentHeader);
        header('Pragma: cache');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header("Content-Length: ".$file_size);
        ob_clean();
        flush();
        exit($content); //输出数据流
    }
}

if ( ! function_exists('nahao_hash'))
{
    /*
     * str: 字符串，待哈希的字符串
     * max_count: 哈希出几个值，比如传值为4，hash会返回1或2或3或4
     *
     */
    function nahao_hash($str, $max_count)
    {
        if( $max_count > 0 )
        {
            $value = sprintf("%u", crc32($str));
            return 1 + $value % $max_count;
        }
        return 1;
    }
}