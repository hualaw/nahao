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

function o($mix_param){
    echo "<pre>";
    var_dump($mix_param);
    exit;
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

if (!function_exists('crm_curl')) {
    function crm_curl($int_user_id){
        $str_random = 'crm_curl';
        $str_param = 'student_id='.$int_user_id;
        $str_param .= '&random_string='.$str_random;
        $str_param .= '&secret_string='.md5(md5($str_random).API_SECRET_STRING_FOR_FRM);
        $curl = curl_init();
        $str_url =  'http://crm.91waijiao.com/api/customer_to_follow?'.$str_param;
//        var_dump($str_url);exit;
        curl_setopt($curl, CURLOPT_URL, $str_url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        $data = curl_exec($curl);
        curl_close($curl);
    }
}

if (!function_exists('getPhoneArea')) {
    function getPhoneArea($phone) {
        $str_url = 'http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=';
        $curl = curl_init($str_url.$phone);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
        return $responseText;
    }
}

/**
 * 根据当前域名生成静态文件的url
 * @param $str_url
 * @return string
 * @author yanrui@tizi.com
 */
function static_url($str_url){
    return STATIC_FILE_URL . $str_url . '?v=' . STATIC_FILE_VERSION;
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