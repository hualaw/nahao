<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "index";
$route['404_override'] = '';

$route['ke_(:num).html'] = 'course/buy_before/$1';
$route['hke_(:num).html'] = 'course/buy_detail/$1';
$route['about/(:any)'] = 'index/about/$1';

/**
 * 那好2.0路由
 * @author jason
 * @param 参数须知：
 *   参数               简称
 * typeId 		=> 		t
 * qualityId	=>		q
 * stageId		=> 		st
 * gradeId		=> 		g
 * subjectId	=> 		su
 * order		=>		o
 * page			=>		p
 */
#1. 列表首页
$route['list.html'] = 'classes/index/';
	//1.1 列表首页 排序
	$route['list_o(:num).html'] = 'classes/index/?order=$1';
	//1.2 列表首页 分页
	$route['list_p(:num).html'] = 'classes/index/?page=$1';
	//1.3 列表首页 排序+分页
	$route['list_o(:num)_p(:num).html'] = 'classes/index/?order=$1&page=$2';

#2. 教育类型 【类型】
$route['list_t(:num).html'] = 'classes/index/?typeId=$1';
	//2.1 教育类型 排序
	$route['list_t(:num)_o(:num).html'] = 'classes/index/?typeId=$1&order=$2';
	//2.2 教育类型 分页
	$route['list_t(:num)_p(:num).html'] = 'classes/index/?typeId=$1&page=$2';
	//2.3 教育类型 排序+分页
	$route['list_t(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&order=$2&page=$3';

#3. 素质教育 【类型+素质】
$route['list_t(:num)_q(:num).html'] = 'classes/index/?typeId=$1&qualityId=$2';
	//3.1 素质教育 类型+素质+排序
	$route['list_t(:num)_q(:num)_o(:num).html'] = 'classes/index/?typeId=$1&qualityId=$2&order=$3';
	//3.2 素质教育 类型+素质+分页
	$route['list_t(:num)_q(:num)_p(:num).html'] = 'classes/index/?typeId=$1&qualityId=$2&page=$3';
	//3.3 素质教育 类型+素质+排序+分页
	$route['list_t(:num)_q(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&qualityId=$2&order=$3&page=$4';

#4. 学科辅导 【类型+学段】
$route['list_t(:num)_st(:num).html'] = 'classes/index/?typeId=$1&stageId=$2';
	//4.1 学科辅导 类型+学段+排序
	$route['list_t(:num)_st(:num)_o(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&order=$3';
	//4.2 学科辅导 类型+学段+分页
	$route['list_t(:num)_st(:num)_p(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&page=$3';
	//4.3 学科辅导 类型+学段+排序+分页
	$route['list_t(:num)_st(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&order=$3&page=$4';

#5. 学科辅导 【类型+年级】
$route['list_t(:num)_g(:num).html'] = 'classes/index/?typeId=$1&gradeId=$2';
	//5.1 学科辅导 类型+年级+排序
	$route['list_t(:num)_g(:num)_o(:num).html'] = 'classes/index/?typeId=$1&gradeId=$2&order=$3';
	//5.2 学科辅导 类型+年级+分页
	$route['list_t(:num)_g(:num)_p(:num).html'] = 'classes/index/?typeId=$1&gradeId=$2&page=$3';
	//5.3 学科辅导 类型+年级+排序+分页
	$route['list_t(:num)_g(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&gradeId=$2&order=$3&page=$4';

#6. 学科辅导 【类型+科目】
$route['list_t(:num)_su(:num).html'] = 'classes/index/?typeId=$1&subjectId=$2';
	//6.1 学科辅导 类型+科目+排序
	$route['list_t(:num)_su(:num)_o(:num).html'] = 'classes/index/?typeId=$1&subjectId=$2&order=$3';
	//6.2 学科辅导 类型+科目+分页
	$route['list_t(:num)_su(:num)_p(:num).html'] = 'classes/index/?typeId=$1&subjectId=$2&page=$3';
	//6.3 学科辅导 类型+科目+排序+分页
	$route['list_t(:num)_su(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&subjectId=$2&order=$3&page=$4';

#7. 学科辅导 【类型+学段+年级】
$route['list_t(:num)_st(:num)_g(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&gradeId=$3';
	//7.1 学科辅导 类型+学段+年级+排序
	$route['list_t(:num)_st(:num)_g(:num)_o(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&gradeId=$3&order=$4';
	//7.2 学科辅导 类型+学段+年级+排序
	$route['list_t(:num)_st(:num)_g(:num)_p(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&gradeId=$3&page=$4';
	//7.3 学科辅导 类型+学段+年级+排序
	$route['list_t(:num)_st(:num)_g(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&gradeId=$3&order=$4&page=$5';

#8. 学科辅导 【类型+学段+科目】
$route['list_t(:num)_st(:num)_su(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&subjectId=$3';
	//8.1 学科辅导 类型+学段+科目+排序
	$route['list_t(:num)_st(:num)_su(:num)_o(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&subjectId=$3&order=$4';
	//8.2 学科辅导 类型+学段+科目+分页
	$route['list_t(:num)_st(:num)_su(:num)_p(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&subjectId=$3&page=$4';
	//8.3 学科辅导 类型+学段+科目+排序+分页
	$route['list_t(:num)_st(:num)_su(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&subjectId=$3&order=$4&page=$5';

#9. 学科辅导 【类型+年级+科目】
$route['list_t(:num)_g(:num)_su(:num).html'] = 'classes/index/?typeId=$1&gradeId=$2&subjectId=$3';
	//9.1 学科辅导 类型+年级+科目+排序
	$route['list_t(:num)_g(:num)_su(:num)_o(:num).html'] = 'classes/index/?typeId=$1&gradeId=$2&subjectId=$3&order=$4';
	//9.2 学科辅导 类型+年级+科目+分页
	$route['list_t(:num)_g(:num)_su(:num)_p(:num).html'] = 'classes/index/?typeId=$1&gradeId=$2&subjectId=$3&page=$4';
	//9.3 学科辅导 类型+年级+科目+排序+分页
	$route['list_t(:num)_g(:num)_su(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&gradeId=$2&subjectId=$3&order=$4&page=$5';

#10. 学科辅导 【类型+学段+年级+科目】
$route['list_t(:num)_st(:num)_g(:num)_su(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&gradeId=$3&subjectId=$4';
	//10.1 学科辅导 类型+学段+年级+科目+排序
	$route['list_t(:num)_st(:num)_g(:num)_su(:num)_o(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&gradeId=$3&subjectId=$4&order=$5';
	//10.2 学科辅导 类型+学段+年级+科目+分页
	$route['list_t(:num)_st(:num)_g(:num)_su(:num)_p(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&gradeId=$3&subjectId=$4&page=$5';
	//10.3 学科辅导 类型+学段+年级+科目+排序+分页
	$route['list_t(:num)_st(:num)_g(:num)_su(:num)_o(:num)_p(:num).html'] = 'classes/index/?typeId=$1&stageId=$2&gradeId=$3&subjectId=$4&order=$5&page=$6';


//$route['course/index/(.*+)/(.*+)']  = 'course/index?round_id=$1&time=$2';
//$route['pay/neworder/(.*+)']  = 'pay/neworder/$1';
//$route['pay/adminOrder/(.*+)/(.*+)']  = 'pay/adminOrder?order_id=$1&payment_method=$2';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
