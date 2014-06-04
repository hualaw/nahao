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


/*  订单状态
 *  -2签名错误 -3金额校验错误
 *  0，未付款；   
 *  1,支付失败     
 *  2，已付款；         
 *  3，已完成（付款完成后7天自动变成这个状态，暂时用不上）；           
 *  4，已取消（用户主动取消）；         
 *  5，已关闭（订单超时，系统自动关闭）；           
 *  6，包含申请退款的轮；            
 *  7，包含退款失败的轮；             
 *  8，包含退款成功的轮；
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
define('ORDER_STATUS_REFUNDSUCC', 7);
define('ORDER_STATUS_REFUNDFAIL', 8);

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
define('DEFAULT_AVATER', '/images/common/default.png');
/*
 * 老师角色
 */
define('TEACH_SPEAKER', 0);
define('TEACH_ASSISTANT', 1);

/*
 * 订单开始值
 */
define('ORDER_START_VALUE',1);



define('CURRENT_TIMESTAMP',time());
define('NH_INIT_PASSWORD','oknahao');

define('STATIC_FILE_URL','http://static'.strstr($_SERVER['HTTP_HOST'], '.'));
//define('STATIC_FILE_PATH',dirname(BASEPATH).'/static');
define('STATIC_FILE_VERSION','0.0.0');
define('__HOST__', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST']:'');

define('TIME_STAMP',time());
define('PER_PAGE_NO','10');


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

//static js
define('STATIC_ADMIN_JS_JQUERY_MIN','/admin/js/jquery_1.10.2.min.js');
define('STATIC_ADMIN_JS_BOOTSTRAP_MIN','/admin/js/bootstrap.min.js');
define('STATIC_ADMIN_JS_BOOTSTRAP_DATETIMEPICKER_MIN','/admin/js/bootstrap-datetimepicker.min.js');
define('STATIC_ADMIN_JS_ADMIN','/admin/js/mod/admin.js');
define('STATIC_ADMIN_JS_ORDER','/admin/js/mod/order.js');
define('STATIC_ADMIN_JS_GROUP','/admin/js/mod/group.js');


define('STATIC_ADMIN_JS_SEA','/public/sea/2.1.0/sea.js');
define('STATIC_ADMIN_JS_CONFIG','/public/config.js');

//static css
define('STATIC_ADMIN_CSS_PUBLIC','/css/adminPublic/style.css');
define('STATIC_ADMIN_CSS_SIGNIN','/css/adminSignin/style.css');
//define('STATIC_ADMIN_CSS_NAV','/css/adminPublic/style.css');
define('STATIC_ADMIN_CSS_BOOTSTRAP','/admin/css/bootstrap.css');
define('STATIC_ADMIN_CSS_SIGNIN','/admin/css/signin.css');
define('STATIC_ADMIN_CSS_BOOTSTRAP_DATETIMEPICKER_MIN','/admin/css/bootstrap-datetimepicker.min.css');


//register type
define('REG_TYPE_PHONE', 1);
define('REG_TYPE_EMAIL', 2);

//login status
define('LOGIN_TYPE_PHONE', 1);
define('LOGIN_TYPE_EMAIL', 2);

//ok/error
define('SUCCESS', 'ok');
define('ERROR', 'error');

//register status
define('REG_SUCCESS', 1);
define('REG_DUP_NICKNAME', 2);
define('REG_DUP_EMAIL', 'dup');
define('REG_DB_ERROR', 4);
define('REG_INVALID_PHONE', 5);
define('REG_INVALID_EMAIL', 6);
define('REG_VERIFY_CAPTCHA_FAILED', 7);
define('REG_PHONE_SERVER_ERROR', 8);
define('REG_DUP_NICKNAME', 9);
define('REG_DUP_PHONE', 10);

//短信发送状态
define('REG_SEND_VERIFY_CODE_FAILED', 11);
define('REG_SEND_VERIFY_CODE_SUCCESS', 12);
//check status
define('REG_CHECK_PHONE_SUCCESS', 13);
define('REG_CHECK_EMAIL_SUCCESS', 14);
define('REG_CHECK_NICKNAME_SUCCESS',15);

//过期时间
define('REDIS_VERIFY_CODE_EXPIRE_TIME', 300); //5分钟
//define('REDIS_VERIFY_CODE_PREFIX', 'PH_');//redis的listkey值不能用纯数字，所以加了个前缀

//phone_server连接
define('PHONE_SERVER_HOST', "192.168.11.75");//线上define('PHONE_SERVER_HOST', "220.181.167.135");//:1899
define('PHONE_SERVER_PORT', 1899);
define('PHONE_SERVER_APPNAME', 'nahao');

//课程中的状态
define('NAHAO_STATUS_COURSE_INIT',0);//初始化
define('NAHAO_STATUS_COURSE_CHECKING',1);//审核中
define('NAHAO_STATUS_COURSE_RUNNING',2);//运营中
define('NAHAO_STATUS_COURSE_PAUSE',3);//暂停
define('NAHAO_STATUS_COURSE_CLOSE',4);//关闭

//验证码类型
define('REGISTER_VERIFY_CODE', 1);
define('BIND_VERIFY_CODE', 2);
define('GET_PASSWORD_VERIFY_CODE', 3);




/* End of file constants.php */
/* Location: ./application/config/constants.php */
