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
define('TABLE_COURSEWARE','courseware');
define('TABLE_ENTERING_CLASSROOM','entering_classroom');
define('TABLE_GROUP_PERMISSION_RELATION','group_permission_relation');
define('TABLE_LESSON','lesson');
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
define('TABLE_STUDENT_INFO','student_info');
define('TABLE_STUDENT_ORDER','student_order');
define('TABLE_STUDENT_REFUND','student_refund');
define('TABLE_STUDENT_SUBJECT','student_subject');
define('TABLE_STUDENT_QUESTION','student_question');
define('TABLE_TEACHER_CHECKOUT_LOG','teacher_checkout_log');
define('TABLE_TEACHER_INFO','teacher_info');
define('TABLE_TEACHER_LECTRUE','teacher_lectrue');
define('TABLE_TEACHER_SUBJECT','teacher_subject');
define('TABLE_USER','user');

//static
define('STATIC_ADMIN_JS_JQUERY_MIN','http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js');
define('STATIC_ADMIN_JS_BOOTSTRAP_MIN','/admin/js/bootstrap.min.js');
define('STATIC_ADMIN_JS_BOOTSTRAP_DATETIMEPICKER_MIN','/admin/js/bootstrap-datetimepicker.min.js');
define('STATIC_ADMIN_JS_ADMIN','/admin/js/mod/admin.js');
define('STATIC_ADMIN_JS_ORDER','/admin/js/mod/order.js');







/* End of file constants.php */
/* Location: ./application/config/constants.php */