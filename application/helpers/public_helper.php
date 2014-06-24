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
function csubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
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

   if($suffix) return $slice."…";

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
//    return STATIC_FILE_URL . $str_url . '?v=' . STATIC_FILE_VERSION;
    return STATIC_FILE_URL . '/'.config_item('static_version').$str_url . '?v=' . config_item('version');
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
function get_course_img_by_size($str_img_url, $str_size){
    $str_return = $str_img_url;
    if(in_array($str_size,array('large','general','small'))){
        $str_img_url .= '?imageView/1/w/';
        if($str_size=='large'){
            $str_img_url .= NH_COURSE_IMG_LARGE_WIDTH.'/h/'.NH_COURSE_IMG_LARGE_HEIGHT;
        }else if($str_size=='general'){
            $str_img_url .= NH_COURSE_IMG_GENERAL_WIDTH.'/h/'.NH_COURSE_IMG_GENERAL_HEIGHT;
        }else if($str_size=='small'){
            $str_img_url .= NH_COURSE_IMG_SMALL_WIDTH.'/h/'.NH_COURSE_IMG_SMALL_HEIGHT;
        }
        $str_return = NH_QINIU_URL.$str_img_url;
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
    }else{
        $str_url .= '?'.http_build_query($arr_param);
    }
//    o($str_url,true);
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
            $arr_param['params'] = json_encode(array('UserName' => 'yanrui'));
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
        $arr_meeting_param = get_meeting_param();
        $arr_param = array_merge($arr_param,$arr_meeting_param);
        $str_response = nh_curl($str_url,$arr_param);
        //log
        if($str_response){
            $arr_response = json_decode($str_response,true);
//            o($str_response);
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
//    o($str_url);
//    o($arr_param);
//    o(json_decode($str_response));
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
    $arr_meeting_param = get_meeting_param();
    $str_response = nh_curl($str_url,$arr_meeting_param,'get');
    if($str_response){
        $arr_response = json_decode($str_response,true);
        $arr_response = isset($arr_response['id']) ? $arr_response : array();
    }
    return $arr_response;
}