<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 全站数据字典
 */

//教师团里的身份
$config['teacher_role'][0] = '主讲';
$config['teacher_role'][1] = '助教';

//教师职称
$config['teacher_title'][0] = '高级教师';
$config['teacher_title'][1] = '一级教师';
$config['teacher_title'][2] = '二级教师';
$config['teacher_title'][3] = '三级教师';

//教学阶段
$config['teach_stage'][0] = '小学';
$config['teach_stage'][1] = '初中';
$config['teach_stage'][2] = '高中';


//轮里面的销售状态
$config['round_sale_status'][0] = '未审核';
$config['round_sale_status'][1] = '审核未通过';
$config['round_sale_status'][2] = '审核通过';
$config['round_sale_status'][3] = '销售中';
$config['round_sale_status'][4] = '已售罄';
$config['round_sale_status'][5] = '已停售';
$config['round_sale_status'][6] = '已下架';

//轮里面的授课状态
$config['round_teach_status'][0] = '等待开课';
$config['round_teach_status'][1] = '授课中';
$config['round_teach_status'][2] = '停课';
$config['round_teach_status'][3] = '结课';

//短信日志表里面的短信类型
$config['message_type'][0] = '注册';
$config['message_type'][1] = '订单';
$config['message_type'][2] = '付款';

//注册页面里面的科目
$config['subject'][0] = '奥数';
$config['subject'][1] = '数学';
$config['subject'][2] = '作文';
$config['subject'][3] = '英语';
$config['subject'][4] = '物理';
$config['subject'][5] = '化学';
$config['subject'][6] = '生物';
$config['subject'][7] = '地理';
$config['subject'][8] = '政治';

//课程类型
$config['course_type'][0] = '教材同步课程';
$config['course_type'][1] = '竞赛考级';
$config['course_type'][2] = '课外教材';
$config['course_type'][3] = '专项训练(知识点)';
$config['course_type'][4] = '素质教育课程';

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
$config['grade'][13] = '其他';


//订单状态
/*
*@author shangshikai@nahao.com
*/
$config["order_status"][0] = "未付款";
$config["order_status"][1] = "支付失败";
$config["order_status"][2] = "已付款";
$config["order_status"][3] = "已完成";
$config["order_status"][4] = "已取消";
$config["order_status"][5] = "已关闭";
$config["order_status"][6] = "包含申请退款的轮";
$config["order_status"][7] = "包含退款失败的轮";
$config["order_status"][8] = "包含退款成功的轮";
//订单支付方式
/*
*@author shangshikai@nahao.com
*/
$config["order_type"][0] = "线上";
$config["order_type"][1] = "网银";
$config["order_type"][2] = "信用卡";
$config["order_type"][3] = "支付宝";
$config["order_type"][4] = "线下";

//用户来源
/*
*@author shangshikai@nahao.com
*/
$config["user_source"][1] = "当当";
$config["user_source"][2] = "58";
$config["user_source"][3] = "拉手";
$config["user_source"][4] = "大街";

//后台会员来源
/*
*@author shangshikai@nahao.com
*/
$config["member_source"][1] = "新浪微博";
$config["member_source"][2] = "有道";
$config["member_source"][3] = "代理商";
$config["member_source"][4] = "网站";
$config["member_source"][5] = "学校企业";
$config["member_source"][6] = "微信推广";

//搜索条件
/*
*@author shangshikai@nahao.com
*/
$config["criteria"][1] = "nickname";
$config["criteria"][2] = "phone";
$config["criteria"][3] = "email";

//全站角色
$config['role'][0] = '系统';
$config['role'][1] = '管理员';
$config['role'][2] = '学生';
$config['role'][3] = '老师';

//进出课堂动作
$config['classroom_action'][1] = '进入';
$config['classroom_action'][2] = '退出';

//课里面的状态
$config['class_teach_status'][0] = '初始化';
$config['class_teach_status'][1] = '即将上课';
$config['class_teach_status'][2] = '正在上课';
$config['class_teach_status'][3] = '上完课';
$config['class_teach_status'][4] = '缺课';
$config['class_teach_status'][3] = '禁用';

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
