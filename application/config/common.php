<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 全站数据字典
 */

//教师团里的身份
$config['teacher_role'][1] = '主讲';
$config['teacher_role'][2] = '助教';

//course status
$config['course_status'][0] = '未审';
$config['course_status'][1] = '拒审';
$config['course_status'][2] = '过审';
$config['course_status'][3] = '暂停';
$config['course_status'][4] = '关闭';

//round_sell_status
$config['round_sale_status'][0] = '未审';
$config['round_sale_status'][1] = '拒审';
$config['round_sale_status'][2] = '过审';//（预售）
$config['round_sale_status'][3] = '在售';
$config['round_sale_status'][4] = '售罄';
$config['round_sale_status'][5] = '停售';//（销售结束时间到，但没售罄，cron改）
$config['round_sale_status'][6] = '下架';//（手动下架，恢复时回到未审核）

//round_teach_status
$config['round_teach_status'][1] = '未开课';
$config['round_teach_status'][2] = '授课中';
$config['round_teach_status'][3] = '停课';//手动操作,恢复时回到1或2，需判断
$config['round_teach_status'][4] = '结课';//上完课cron修改为此状态
$config['round_teach_status'][5] = '过期';//(结课后一个月cron会把这个状态改为过期)

//性别
$config['gender'][1] = '男';
$config['gender'][2] = '女';

//教育阶段
$config['stage'][1] = '小学';
$config['stage'][2] = '初中';
$config['stage'][3] = '高中';

$config['has_bought'][1] = '注册用户';
$config['has_bought'][2] = '付费用户';

//短信日志表里面的短信类型
$config['message_type'][0] = '注册';
$config['message_type'][1] = '订单';
$config['message_type'][2] = '付款';

//授课类型
$config['teach_type'][0] = '一对一';
$config['teach_type'][1] = '小班教育(15人以内)';
$config['teach_type'][2] = '大班教育不限人数';

//年级
$config['grade'][1] = '一年级';
$config['grade'][2] = '二年级';
$config['grade'][3] = '三年级';
$config['grade'][4] = '四年级';
$config['grade'][5] = '五年级';
$config['grade'][6] = '六年级';
$config['grade'][7] = '初一';
$config['grade'][8] = '初二';
$config['grade'][9] = '初三';
$config['grade'][10] = '高一';
$config['grade'][11] = '高二';
$config['grade'][12] = '高三';
$config['grade'][99] = '其他';

//订单状态

$config["order_status"][0] = "未付款";
$config["order_status"][1] = "支付失败";
$config["order_status"][2] = "已付款";
$config["order_status"][3] = "已完成";
$config["order_status"][4] = "已取消";
$config["order_status"][5] = "已关闭";
$config["order_status"][6] = "申请退款";
$config["order_status"][7] = "拒绝退款";
$config["order_status"][8] = "同意退款";
$config["order_status"][9] = "退款完成";
//订单支付方式

$config["order_type"][0] = "线上";
$config["order_type"][1] = "网银";
$config["order_type"][2] = "信用卡";
$config["order_type"][3] = "支付宝";
$config["order_type"][4] = "线下";

//用户来源

$config["user_source"][1] = "当当";
$config["user_source"][2] = "58";
$config["user_source"][3] = "拉手";
$config["user_source"][4] = "大街";

//后台会员来源

$config["member_source"][1] = "新浪微博";
$config["member_source"][2] = "有道";
$config["member_source"][3] = "代理商";
$config["member_source"][4] = "网站";
$config["member_source"][5] = "学校企业";
$config["member_source"][6] = "微信推广";

//订单搜索条件
/*
*@author shangshikai@nahao.com
*/
$config["criteria"][1] = "nickname";
$config["criteria"][2] = "phone";
$config["criteria"][3] = "email";


//试讲搜索条件
/*
*@author shangshikai@nahao.com
*/
$config['lecture_factor'][1] = 'course';
$config['lecture_factor'][2] = 'name';
$config['lecture_factor'][3] = 'phone';


//退款状态
/*
*@author shangshikai@nahao.com
*/
$config['refund'][0] = "初始化";
$config['refund'][1] = "缺席";
$config['refund'][2] = "进过教室";
$config['refund'][3] = "申请退款";
$config['refund'][4] = "退款通过";
$config['refund'][5] = "退款不通过";
$config['refund'][5] = "退款已完成";


//进出课堂动作
$config['classroom_action'][1] = '进入';
$config['classroom_action'][2] = '退出';

//课里面的状态
$config['class_teach_status'][0] = '初始化';
$config['class_teach_status'][1] = '即将上课';
$config['class_teach_status'][2] = '可进教室';
$config['class_teach_status'][3] = '正在上课';
$config['class_teach_status'][4] = '上完课';
$config['class_teach_status'][5] = '缺课';
$config['class_teach_status'][6] = '禁用';



//教师试讲状态
$config['lecture_status'][1] = '未审核';
$config['lecture_status'][2] = '待定';
$config['lecture_status'][3] = '审核未通过';
$config['lecture_status'][4] = '审核通过';

//授课方式
$config['teacher_type'][1] = '一对一';
$config['teacher_type'][2] = '小班教育(15人以内)';
$config['teacher_type'][3] = '大班教育不限人数';

//职称
$config['teacher_title'][0] = '无职称';
$config['teacher_title'][1] = '正高级教师';
$config['teacher_title'][2] = '高级教师';
$config['teacher_title'][3] = '一级教师';
$config['teacher_title'][4] = '二级教师';
$config['teacher_title'][5] = '三级教师';



//退款状态
/*
*@author shangshikai@nahao.com
*/
$config['refund'][0] = "初始化";
$config['refund'][1] = "缺席";
$config['refund'][2] = "进过教室";
$config['refund'][3] = "申请退款";
$config['refund'][4] = "退款通过";
$config['refund'][5] = "退款不通过";
$config['refund'][5] = "退款已完成";


//银行

$config['bank'][1] = "招商银行";
$config['bank'][2] = "工商银行";
$config['bank'][3] = "中国银行";
$config['bank'][4] = "交通银行";
$config['bank'][5] = "建设银行";
$config['bank'][6] = "农业银行";
$config['bank'][7] = "中信银行";
$config['bank'][8] = "浦发银行";
$config['bank'][9] = "兴业银行";
$config['bank'][10] = "深圳发展银行";
$config['bank'][11] = "民生银行";
$config['bank'][12] = "光大银行";

//订单日子action
$config['order_log_action'][0] = "创建订单";
$config['order_log_action'][1] = "支付失败";
$config['order_log_action'][2] = "完成付款";
$config['order_log_action'][3] = "订单完成";
$config['order_log_action'][4] = "取消订单";
$config['order_log_action'][5] = "关闭订单";
$config['order_log_action'][6] = "备注";
$config['order_log_action'][7] = "删除订单";
$config['order_log_action'][8] = "申请退款";
$config['order_log_action'][9] = "拒绝退款";
$config['order_log_action'][10] = "同意退款";
$config['order_log_action'][11] = "完成退款";

//学校类型
$config['school_type'][1] = '公立小学';
$config['school_type'][2] = '公立中学';

//进出课堂动作
$config['classroom_action'][1] = '进入';
$config['classroom_action'][2] = '退出';

//课堂学生小动作
$config['classroom_student_action'][1] = '点赞';
$config['classroom_student_action'][2] = '讲快点';
$config['classroom_student_action'][3] = '讲慢点';


//账户状态
$config['account'][0] = '禁用';
$config['account'][1] = '启用';

//进入meeting的身份类型
$config['nh_meeting_type'][0] = '学生';
$config['nh_meeting_type'][1] = '老师';
$config['nh_meeting_type'][2] = '管理员';
$config['nh_meeting_type'][110] = '超级管理员';//上传pdf时候用。如果user_type为110 那么就不会校验meeting_id 其他的会校验meeting_id

//admin_course_list_search_type
$config['admin_course_list_search_type'][1] = '课程名称';
$config['admin_course_list_search_type'][2] = '授课老师';
$config['admin_course_list_search_type'][3] = '课程ID';

//admin_round_list_search_type
$config['admin_round_list_search_type'][1] = '班次名称';
$config['admin_round_list_search_type'][2] = '授课老师';
$config['admin_round_list_search_type'][3] = '班次ID';

//admin_student_list_search_type
$config['admin_student_list_search_type'][1] = '昵称';
$config['admin_student_list_search_type'][2] = '邮箱';
$config['admin_student_list_search_type'][3] = '手机号';
$config['admin_student_list_search_type'][4] = '用户ID';


//老师课酬结算状态
$config['teacher_balance'][1]='未结算';
$config['teacher_balance'][2]='已结算';
$config['teacher_balance'][3]='已付款';

//limit of avatar's size
$config['avatar_size_limit'] = 1024 * 1024 * 2;//2M

//image type  这个数组的键值根据php getimagesize 返回的类型来定
$config['image_type'][1] = 'GIF';
$config['image_type'][2] = 'JPG';
$config['image_type'][3] = 'PNG';

//每一节课的结算状态
$config['class_checkout_status'][0] = '不可结算';
$config['class_checkout_status'][1] = '可结算';
$config['class_checkout_status'][2] = '已结算';

//课评价显示状态
$config['feedback'][0] = '屏蔽';
$config['feedback'][1] = '显示';


//公告状态
$config['affiche_status'][1] = '未审核';
$config['affiche_status'][2] = '审核不通过';
$config['affiche_status'][3] = '审核通过';

//生成测试轮时候的时间间隔配置
$config['testing_round_time_config']['before_sell_begin_time_min'] = 300;//销售时间最早在创建轮时间之后五分钟
$config['testing_round_time_config']['before_sell_begin_time_max'] = 86400*30;//销售时间最晚在创建轮时间之后一个月
$config['testing_round_time_config']['before_sell_end_time_min'] = 300;//最短销售期
$config['testing_round_time_config']['before_sell_end_time_max'] = 86400*30*2;//最长销售期
$config['testing_round_time_config']['before_begin_time'] = 300;//停卖多久后开课
$config['testing_round_time_config']['before_first_class'] = 300;//轮开课与第一节课间隔
$config['testing_round_time_config']['class_min_long'] = 300;//最短上课时间
$config['testing_round_time_config']['class_max_long'] = 3*3600;//最长上课时间
$config['testing_round_time_config']['class_between_long'] = 300;//课间隔
$config['testing_round_time_config']['enter_before_class'] = 120;//提前进教室时间
$config['testing_round_time_config']['teacher_late_time'] = 120;//开课后多久老师点上课算迟到


//生成标准轮时候的时间间隔配置
$config['production_round_time_config']['before_sell_begin_time_min'] = 300;//销售时间最早在创建轮时间之后一天
$config['production_round_time_config']['before_sell_begin_time_max'] = 86400*30;////销售时间最晚在创建轮时间之后一个月
$config['production_round_time_config']['before_sell_end_time_min'] = 86400;//最短销售期
$config['production_round_time_config']['before_sell_end_time_max'] = 86400*30*2;//最长销售期
$config['production_round_time_config']['before_begin_time'] = 86400;//停卖多久后开课
$config['production_round_time_config']['before_first_class'] = 3600;//轮开课与第一节课间隔
$config['production_round_time_config']['class_min_long'] = 30*60;//最短上课时间
$config['production_round_time_config']['class_max_long'] = 3*3600;//最长上课时间
$config['production_round_time_config']['class_between_long'] = 3600;//课间隔
$config['production_round_time_config']['enter_before_class'] = 1800;//提前进教室时间
$config['production_round_time_config']['teacher_late_time'] = 300;//开课后多久老师点上课算迟到

/**
 * 劳务报酬个人计算税率 之 含税级距参数表
 * 计算说明：
 * 1. 如果收入<=4000大于800 公式为 A：税费=（收入-800）*税率
 * 
 * 2. 如果收入>4000 公式为 B：税费 = 收入*（1-20%）*级距税率-速算扣除数
 * 		2.1 再根据 收入*（1-20%）所在【含税级距】的级别的参数去使用 【公式B】 计算
 * 
 * 3. 小于 800不纳税
 */

$config['tax_rate_config'][1] = array(
		'lever' 			=> 1, //级数
		'range_explain'		=> '大于800小于等于4000',
		'range_from' 		=> 800,//含税级距范围开始值
		'range_to' 			=> 4000,//含税级距范围结束值(含)
		'tax_rate' 			=> 0.2,//税率
		'quick_deduction'	=> 0,//速算扣除数
	);
$config['tax_rate_config'][2] = array(
		'lever' 			=> 2, //级数
		'range_explain'		=> '大于4000不超过20000元的部分',
		'range_from' 		=> 4000,//含税级距范围开始值
		'range_to' 			=> 20000,//含税级距范围结束值(含)
		'tax_rate' 			=> 0.2,//税率
		'quick_deduction'	=> 0,//速算扣除数
	);
$config['tax_rate_config'][3] = array(
		'lever' 			=> 3, //级数
		'range_explain'		=> '超过20000元至50000元的部分',
		'range_from' 		=> 20000,//含税级距范围开始值
		'range_to' 			=> 50000,//含税级距范围结束值(含)
		'tax_rate' 			=> 0.3,//税率
		'quick_deduction'	=> 2000,//速算扣除数
	);
$config['tax_rate_config'][4] = array(
		'lever' 			=> 4, //级数
		'range_explain'		=> '超过50000元的部分',
		'range_from' 		=> 50000,//含税级距范围开始值
		'range_to' 			=> '',//【为空不判断极值】
		'tax_rate' 			=> 0.4,//税率
		'quick_deduction'	=> 7000,//速算扣除数
	);
