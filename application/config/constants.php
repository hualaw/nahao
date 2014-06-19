<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


//********** NAHAO **********


define('ROLE_SYS', 0);//系统
define('ROLE_ADMIN', 1);//管理员
define('ROLE_STUDENT', 2);//学生
define('ROLE_TEACHER', 3);//教师

//GENDER
define('NH_GENDER_MALE', 1);//男
define('NH_GENDER_FEMALE', 2);//女
define('NH_GENDER_SECRET', 3);//保密


/*  订单状态
 *  -2签名错误 -3金额校验错误
 *  0，未付款；   
 *  1,支付失败     
 *  2，已付款；         
 *  3，已完成（付款完成后7天自动变成这个状态，暂时用不上）；           
 *  4，已取消（用户主动取消）；         
 *  5，已关闭（订单超时，系统自动关闭）；           
 *  6，申请退款；            
 *  7，退款失败；             
 *  8，同意退款；
 *  9,退款完成
 *  */
define('ORDER_STATUS_DEBT', -3);
define('ORDER_STATUS_SIGN', -2);
define('ORDER_STATUS_INIT', 0);
define('ORDER_STATUS_FAIL', 1);
define('ORDER_STATUS_SUCC', 2);
define('ORDER_STATUS_FINISH', 3);
define('ORDER_STATUS_CANCEL', 4);
define('ORDER_STATUS_CLOSE', 5);
define('ORDER_STATUS_APPLYREFUND', 6);
define('ORDER_STATUS_APPLYREFUND_FAIL', 7);
define('ORDER_STATUS_APPLYREFUND_AGREE', 8);
define('ORDER_STATUS_APPLYREFUND_SUCC', 9);

/*
 * 订单支付方式
 */
define('ORDER_TYPE_ONLINE', 0);
define('ORDER_TYPE_ALIPAY', 3);
define('ORDER_TYPE_OFFLINE', 4);

/*
 * 首页列表默认图片 HOME_IMG_DEFAULT
 * 默认头像 DEFAULT_AVATER
 */
define('HOME_IMG_DEFAULT', '/images/studentHomePage/course1.jpg');
define('DEFAULT_STUDENT_AVATER', '/images/login/default_avatar.png');
define('DEFAULT_TEACHER_AVATER', '/images/login/default_avatar.png');
/*
 * 老师角色
 * 主讲 0
 * 助教 1
 */
define('TEACH_SPEAKER', 0);
define('TEACH_ASSISTANT', 1);

/*
 * 订单编号开始值
 */
define('ORDER_START_VALUE',1);

/*
 * 订单日志里面的action
 * 创建订单 0
 * 支付失败1
 * 完成付款2
 * 订单完成（付款完成后7天自动变成这个状态，暂时用不上）3
 * 取消订单（用户主动取消） 4
 * 关闭订单（订单超时，系统自动关闭） 5
 * 备注 6
 * 删除订单 7
 * 申请退款8 
 * 拒绝退款 9 
 * 同意退款10
 * 完成退款 11
 */
define('ORDER_ACTION_CREATE_ORDER', 0);
define('ORDER_ACTION_FAIL', 1);
define('ORDER_ACTION_SUCC', 2);
define('ORDER_ACTION_FINISH', 3);
define('ORDER_ACTION_CANCEL', 4);
define('ORDER_ACTION_CLOSE', 5);
define('ORDER_ACTION_BEIZU', 6);
define('ORDER_ACTION_DELETE_ORDER', 7);
define('ORDER_ACTION_APPLY_REFUND', 8);
define('ORDER_ACTION_REFUND_FAIL', 9);
define('ORDER_ACTION_REFUND_AGREE', 10);
define('ORDER_ACTION_REFUND_FAINSH', 11);

define('CURRENT_TIMESTAMP',time());
define('NH_INIT_PASSWORD','oknahao');

define('STATIC_FILE_URL','http://static'.strstr($_SERVER['HTTP_HOST'], '.'));
//define('STATIC_FILE_URL','http://www'.strstr($_SERVER['HTTP_HOST'], '.').'/static');
//define('STATIC_FILE_PATH',dirname(BASEPATH).'/static');
define('STATIC_FILE_VERSION','0.0.0');
define('__HOST__', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST']:'');

define('TIME_STAMP',time());
define('PER_PAGE_NO',10);

//tables
define('TABLE_ADMIN','admin');
define('TABLE_ADMIN_GROUP','admin_group');
define('TABLE_ADMIN_PERMISSION_RELATION','admin_permission_relation');
define('TABLE_CLASS','class');
define('TABLE_CLASS_DISCUSS_LOG','class_discuss_log');
define('TABLE_CLASS_FEEDBACK','class_feedback');
define('TABLE_COURSE','course');
define('TABLE_COURSE_GRADE_RELATION','course_grade_relation');
define('TABLE_COURSE_TEACHER_RELATION','course_teacher_relation');
define('TABLE_COURSE_TYPE', 'course_type');
define('TABLE_COURSEWARE','courseware');
define('TABLE_ENTERING_CLASSROOM','entering_classroom');
define('TABLE_GROUP_PERMISSION_RELATION','group_permission_relation');
define('TABLE_LESSON','lesson');
define('TABLE_NAHAO_AREAS','nahao_areas');
define('TABLE_NAHAO_SCHOOLS','nahao_schools');
define('TABLE_SCHOOLS_CREATE', 'nahao_schools_create');
define('TABLE_ORDER_ROUND_RELATION','order_round_relation');
define('TABLE_PERMISSION','permission');
define('TABLE_QUESTION','question');
define('TABLE_QUESTION_CLASS_RELATION','question_class_relation');
define('TABLE_QUESTION_LESSON_RELATION','question_lesson_relation');
define('TABLE_ROUND','round');
define('TABLE_ROUND_NOTE','round_note');
define('TABLE_ROUND_TEACHER_RELATION','round_teacher_relation');
define('TABLE_SHOPPING_CART','shopping_cart');
define('TABLE_SMS_LOG','sms_log');
define('TABLE_SMS_VERIFY_CODE','sms_verify_code');
define('TABLE_STUDENT_CLASS','student_class');
define('TABLE_STUDENT_CLASS_LOG','student_class_log');
define('TABLE_STUDENT_ORDER','student_order');
define('TABLE_STUDENT_REFUND','student_refund');
define('TABLE_STUDENT_SUBJECT','student_subject');
define('TABLE_STUDENT_QUESTION','student_question');
define('TABLE_TEACHER_CHECKOUT_LOG','teacher_checkout_log');
define('TABLE_TEACHER_LECTRUE','teacher_lectrue');
define('TABLE_TEACHER_SUBJECT','teacher_subject');
define('TABLE_USER','user');
define('TABLE_USER_INFO','user_info');
define('TABLE_SESSION_LOG', 'session_log');
define('TABLE_SUBJECT', 'subject');
define('TABLE_CLASS_NOTE', 'class_note');

//static js
define('STATIC_ADMIN_JS_JQUERY_MIN','/admin/js/jquery_1.10.2.min.js');
define('STATIC_ADMIN_JS_BOOTSTRAP_MIN','/admin/js/bootstrap.min.js');
define('STATIC_ADMIN_JS_BOOTSTRAP_DATETIMEPICKER_MIN','/admin/js/bootstrap-datetimepicker.min.js');
define('STATIC_ADMIN_JS_ADMIN','/admin/js/mod/admin.js');
define('STATIC_ADMIN_JS_ORDER','/admin/js/mod/adminOrder.js');
define('STATIC_ADMIN_JS_LECTURE','/admin/js/mod/lecture.js');
define('STATIC_ADMIN_JS_GROUP','/admin/js/mod/group.js');


define('STATIC_ADMIN_JS_SEA','/public/sea/2.1.0/sea.js');
define('STATIC_ADMIN_JS_CONFIG','/public/config.js');

//static css
define('STATIC_ADMIN_CSS_PUBLIC','/css/adminPublic/style.css');
define('STATIC_ADMIN_CSS_SIGNIN','/css/adminSignin/style.css');
//define('STATIC_ADMIN_CSS_NAV','/css/adminPublic/style.css');
define('STATIC_ADMIN_CSS_BOOTSTRAP','/admin/css/bootstrap.css');
define('STATIC_ADMIN_CSS_BOOTSTRAP_DATETIMEPICKER_MIN','/admin/css/bootstrap-datetimepicker.min.css');

//register&login type
define('REG_LOGIN_TYPE_PHONE', 1);
define('REG_LOGIN_TYPE_EMAIL', 2);

define('PHONE_SERVER_APPNAME', 'nahao');

//ok/error
define('SUCCESS', 'ok');
define('ERROR', 'error');

//过期时间
define('REDIS_VERIFY_CODE_EXPIRE_TIME', 3600); //测试暂定1小时，上线应该改为5分钟

//课程中的状态
define('NAHAO_STATUS_COURSE_INIT',0);//未审核
define('NAHAO_STATUS_COURSE_CHECKING',1);//审核未通过
define('NAHAO_STATUS_COURSE_RUNNING',2);//审核通过，运营中
define('NAHAO_STATUS_COURSE_PAUSE',3);//暂停
define('NAHAO_STATUS_COURSE_CLOSE',4);//关闭

//验证码类型
define('REGISTER_VERIFY_CODE', 1);
define('BIND_VERIFY_CODE', 2);
define('GET_PASSWORD_VERIFY_CODE', 3);

//七牛账号
define('NH_QINIU_ACCESS_KEY','M_oyP-OlxZM8wY1JuaqU4MXrVjSEm4wCnABxVZOq');
define('NH_QINIU_SECRET_KEY','RWYYV4CTc7TCjfFOPFzH0Id1LiuiQXE8chXHv8pi');
define('NH_QINIU_BUCKET','n1a2h3a4o5');
define('NH_QINIU_URL', 'http://n1a2h3a4o5.qiniudn.com/');

define('MAX_NICKNAME_LEN', 25);

//meeting account
define('NH_MEETING_URL','http://classroom.oa.tizi.com:80/');
define('NH_MEETING_ACCESS_KEY','525510');
define('NH_MEETING_SECRET_KEY','311ba4ffe6c74dd9af480d8411edc44e');
//访问教室的用户类型
define('NH_MEETING_TYPE_STUDENT',0);//学生
define('NH_MEETING_TYPE_TEACHER',1);//老师
define('NH_MEETING_TYPE_ADMIN',2);//管理员
define('NH_MEETING_TYPE_SUPER_ADMIN',110);//超级管理员
//进教室的链接，后面拼token就能进了
//define('NH_MEETING_ENTER_URL','http://classroom.oa.tizi.com/oa/enter?token=');
define('NH_MEETING_ENTER_URL','http://classroom.oa.tizi.com/nahao/enter?token=');

//课程封面图的三个尺寸 290*216  227*169   66*49
define('NH_COURSE_IMG_LARGE_HEIGHT',216);
define('NH_COURSE_IMG_LARGE_WIDTH',290);
define('NH_COURSE_IMG_GENERAL_HEIGHT',169);
define('NH_COURSE_IMG_GENERAL_WIDTH',227);
define('NH_COURSE_IMG_SMALL_HEIGHT',49);
define('NH_COURSE_IMG_SMALL_WIDTH',66);
define('NH_TEACHER_IMG_HEIGHT', 225);
define('NH_TEACHER_IMG_WIDTH', 300);

//单轮，单节最大人数
define('NH_CLASS_PEOPLE_CAPS',100);

//教龄上限
define('TEACHER_AGE_CEILING', 50);

//教室学生操作类型定义
define('CLASS_PLEASE_ACTION', 1);//赞
define('CLASS_SLOWER_ACTION', 2);//讲快一点
define('CLASS_FASTER_ACTION', 3);//讲慢一点

/* End of file constants.php */
/* Location: ./application/config/constants.php */
