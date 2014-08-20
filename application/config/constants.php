<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

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
/**
 * 那好2.0芝麻开门
 * @命名规则: 	
 * 列表页猜您喜欢：	控制器_方法_块名_SWITCH =》 CLASSES_INDEX_SUGGEST_SWITCH
 */ 
define('CLASSES_INDEX_SUGGEST_SWITCH',1);			// 列表页 		> 猜您喜欢
define('CLASSES_INDEX_BROWSING_HISTORY_SWITCH',1);	// 列表页 		> 浏览记录
define('BUY_BEFORE_RECOMMEND_SWITCH',1);			// 课程详情页 	> 看了又看
//switch
define('SWITCH_WWW_INDEX_LIVE_SHOW',1);
define('SWITCH_WWW_INDEX_COURSE_LIST',1);

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

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');


//********** NAHAO **********

//GENDER
define('NH_GENDER_MALE', 1); //男
define('NH_GENDER_FEMALE', 2); //女
define('NH_GENDER_SECRET', 3); //保密


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
define('ORDER_TYPE_BANK', 1);
define('ORDER_TYPE_CREDITPAY', 2);
define('ORDER_TYPE_ALIPAY', 3);
define('ORDER_TYPE_OFFLINE', 4);

/*
 * 首页列表默认图片 HOME_IMG_DEFAULT
 * 默认头像 DEFAULT_AVATER  
 */
define('HOME_IMG_DEFAULT', '/images/studentHomePage/course1.jpg');
define('DEFAULT_STUDENT_AVATER', '/images/login/default_avatar.png');
define('DEFAULT_TEACHER_AVATER', '/images/login/default_avatar.png');
define('DEFAULT_MANGER_AVATER', '/images/login/manger.png');
/*
 * 老师角色
 * 主讲 1
 * 助教 2
 */
define('TEACH_SPEAKER', 1);
define('TEACH_ASSISTANT', 2);

/*
 * 订单编号开始值
 */
define('ORDER_START_VALUE', 1);

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

// 课的状态
define('CLASS_STATUS_INIT', 0); //初始化
define('CLASS_STATUS_SOON_CLASS', 1); //即将上课
define('CLASS_STATUS_ENTER_ROOM', 2); //可进教室
define('CLASS_STATUS_CLASSING', 3); //正在上课
define('CLASS_STATUS_CLASS_OVER', 4); //上完课
define('CLASS_STATUS_MISS_CLASS', 5); //老师缺课
define('CLASS_STATUS_FORI_CLASS', 6); //禁用（不能恢复）

/**
 * 轮的销售状态
 * 未审核0
 * 审核不通过1
 * 审核通过（预售）2
 * 销售中3
 * 已售罄4
 * 已停售（时间到了还没售罄）5
 * 已下架（手动下架）6
 */
define('ROUND_SALE_STATUS_INIT', 0);
define('ROUND_SALE_STATUS_NO_PASS', 1);
define('ROUND_SALE_STATUS_PASS', 2);
define('ROUND_SALE_STATUS_SALE', 3);
define('ROUND_SALE_STATUS_OVER', 4);
define('ROUND_SALE_STATUS_FINISH', 5);
define('ROUND_SALE_STATUS_OFF', 6);

/**
 * 轮的授课状态
 * 等待开课1
 * 授课中2
 * 停课（手动操作）3
 * 结课4
 * 过期(节课后一个月cron会把这个状态改为过期)5
 */
define('ROUND_TEACH_STATUS_INIT', 1);
define('ROUND_TEACH_STATUS_TEACH', 2);
define('ROUND_TEACH_STATUS_STOP', 3);
define('ROUND_TEACH_STATUS_FINISH', 4);
define('ROUND_TEACH_STATUS_OVER', 5);


/**
 * 学生退款记录表student_refund退款状态
 * 处理中 0
 * 退款失败1
 * 同意退款2
 * 退款完成3
 */
define('REFUND_STATUS_INIT', 0);
define('REFUND_STATUS_FAIL', 1);
define('REFUND_STATUS_AGREE', 2);
define('REFUND_STATUS_FINISH', 3);

/**
 *
 * 学生与课的关系表里面的状态 student_class
 * 初始化（未到上课时间）0
 * 缺席（进入教师按钮可用时间段内，学生没点过此按钮算缺席）1
 * 进过教室 2
 * 申请退款3
 * 退款同意 4
 * 完成退款5
 * 退款失败6
 */
define('STUDENT_CLASS_INIT', 0);
define('STUDENT_CLASS_LOST', 1);
define('STUDENT_CLASS_ENTER', 2);
define('STUDENT_CLASS_APPLY_REFUND', 3);
define('STUDENT_CLASS_REFUND_AGREE', 4);
define('STUDENT_CLASS_REFUND_FINISH', 5);
define('STUDENT_CLASS_REFUND_FAIL', 6);

define('CURRENT_TIMESTAMP', time());
define('NH_INIT_PASSWORD', 'oknahao');

define('STATIC_FILE_URL', 'http://static' . strstr($_SERVER['HTTP_HOST'], '.'));
//define('STATIC_FILE_URL','http://www'.strstr($_SERVER['HTTP_HOST'], '.').'/static');
//define('STATIC_FILE_PATH',dirname(BASEPATH).'/static');
define('STATIC_FILE_VERSION', '0.0.0');
define('__HOST__', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');

define('TIME_STAMP', time());
define('PER_PAGE_NO', 10);

//tables
define('TABLE_ADMIN', 'admin');
define('TABLE_ADMIN_GROUP', 'admin_group');
define('TABLE_ADMIN_PERMISSION_RELATION', 'admin_permission_relation');
define('TABLE_CLASS', 'class');
define('TABLE_CLASS_ACTION_LOG', 'class_action_log');
define('TABLE_CLASS_DISCUSS_LOG', 'class_discuss_log');
define('TABLE_CLASS_FEEDBACK', 'class_feedback');
define('TABLE_FEEDBACK', 'feedback');
define('TABLE_COURSE', 'course');
define('TABLE_COURSE_GRADE_RELATION', 'course_grade_relation');
define('TABLE_COURSE_TEACHER_RELATION', 'course_teacher_relation');
define('TABLE_COURSE_TYPE', 'course_type');
define('TABLE_COURSEWARE', 'courseware');
define('TABLE_ENTERING_CLASSROOM', 'entering_classroom');
define('TABLE_GROUP_PERMISSION_RELATION', 'group_permission_relation');
define('TABLE_LESSON', 'lesson');
define('TABLE_NAHAO_AREAS', 'nahao_areas');
define('TABLE_NAHAO_SCHOOLS', 'nahao_schools');
define('TABLE_SCHOOLS_CREATE', 'nahao_schools_create');
define('TABLE_ORDER_ROUND_RELATION', 'order_round_relation');
define('TABLE_PERMISSION', 'permission');
define('TABLE_QUESTION', 'question');
define('TABLE_QUESTION_CLASS_RELATION', 'question_class_relation');
define('TABLE_QUESTION_LESSON_RELATION', 'question_lesson_relation');
define('TABLE_RESOURCE', 'resource');
define('TABLE_ROUND', 'round');
define('TABLE_ROUND_NOTE', 'round_note');
define('TABLE_ROUND_TEACHER_RELATION', 'round_teacher_relation');
define('TABLE_SHOPPING_CART', 'shopping_cart');
define('TABLE_SMS_LOG', 'sms_log');
define('TABLE_SMS_VERIFY_CODE', 'sms_verify_code');
define('TABLE_STUDENT_CLASS', 'student_class');
define('TABLE_STUDENT_ORDER', 'student_order');
define('TABLE_STUDENT_REFUND', 'student_refund');
define('TABLE_STUDENT_SUBJECT', 'student_subject');
define('TABLE_STUDENT_QUESTION', 'student_question');
define('TABLE_TEACHER_CHECKOUT_LOG', 'teacher_checkout_log');
define('TABLE_TEACHER_LECTURE', 'teacher_lecture');
define('TABLE_TEACHER_SUBJECT', 'teacher_subject');
define('TABLE_USER', 'user');
define('TABLE_USER_INFO', 'user_info');
define('TABLE_SESSION_LOG', 'session_log');
define('TABLE_SUBJECT', 'subject');
define('TABLE_CLASS_NOTE', 'class_note');
define('TABLE_ORDER_ACTION_LOG', 'order_action_log');
define('TABLE_FOCUS_PHOTO', 'focus_photo');
define('TABLE_LECTURE_CLASS', 'lecture_class');
define('TABLE_EMPLOYMENT', 'employment');
//各表中字段数据字典  规则: 1按表名字母排序 2表名大写 3字段名小写

//ROUND sale_status
define('TABLE_ROUND_DIC_SALE_STATUS_INIT', 0); //未审核
define('TABLE_ROUND_DIC_SALE_STATUS_DENY', 1); //审核未通过
define('TABLE_ROUND_DIC_SALE_STATUS_RUNNING', 2); //审核通过（进入预售期）
define('TABLE_ROUND_DIC_SALE_STATUS_ON_SALE', 3); //在售
define('TABLE_ROUND_DIC_SALE_STATUS_SOLD_OUT', 4); //售罄
define('TABLE_ROUND_DIC_SALE_STATUS_STOP', 5); //停售（时间到）
define('TABLE_ROUND_DIC_SALE_STATUS_OFFLINE', 6); //下架

//ROUND teach_status
define('TABLE_ROUND_DIC_TEACH_STATUS_INIT', 1); //等待开课
define('TABLE_ROUND_DIC_TEACH_STATUS_RUNNING', 2); //授课中
define('TABLE_ROUND_DIC_TEACH_STATUS_STOP', 3); //停课（手动操作）
define('TABLE_ROUND_DIC_TEACH_STATUS_FINISH', 4); //结课
define('TABLE_ROUND_DIC_TEACH_STATUS_EXPIRE', 5); //过期(结课后一个月cron会把结课改为过期)

//USER status
define('TABLE_USER_DIC_STATUS_OFF', 0);
define('TABLE_USER_DIC_STATUS_ON', 1);

//USER teach_priv
define('TABLE_USER_DIC_TEACH_PRIV_OFF', 0);
define('TABLE_USER_DIC_TEACH_PRIV_ON', 1);

//static js
define('STATIC_ADMIN_JS_JQUERY_MIN', '/admin/js/jquery_1.10.2.min.js');
define('STATIC_ADMIN_JS_BOOTSTRAP_MIN', '/admin/js/bootstrap.min.js');
define('STATIC_ADMIN_JS_BOOTSTRAP_DATETIMEPICKER_MIN', '/admin/js/bootstrap-datetimepicker.min.js');
define('STATIC_ADMIN_JS_ADMIN', '/admin/js/mod/admin.js');
define('STATIC_ADMIN_JS_ORDER', '/admin/js/mod/adminOrder.js');
define('STATIC_ADMIN_JS_LECTURE', '/admin/js/mod/lecture.js');
define('STATIC_ADMIN_JS_GROUP', '/admin/js/mod/group.js');


define('STATIC_ADMIN_JS_SEA', '/public/sea/2.1.0/sea.js');
define('STATIC_ADMIN_JS_CONFIG', '/public/config.js');

//static css
define('STATIC_ADMIN_CSS_PUBLIC', '/css/adminPublic/style.css');
define('STATIC_ADMIN_CSS_CLASSROOM', '/css/classRoom/style.css');
define('STATIC_ADMIN_CSS_PREVIEW', '/css/adminPreview/style.css');
define('STATIC_ADMIN_CSS_SIGNIN', '/css/adminSignin/style.css');
//define('STATIC_ADMIN_CSS_NAV','/css/adminPublic/style.css');
define('STATIC_ADMIN_CSS_BOOTSTRAP', '/admin/css/bootstrap.css');
define('STATIC_ADMIN_CSS_BOOTSTRAP_DATETIMEPICKER_MIN', '/admin/css/bootstrap-datetimepicker.min.css');

//register&login type
define('REG_LOGIN_TYPE_PHONE', 1);
define('REG_LOGIN_TYPE_EMAIL', 2);
define('REG_LOGIN_TYPE_THIRD_PART', 3);

//phone_verified type
define('PHONE_UNVERIFIED', 0);
define('PHONE_VERIFIED', 1);

define('PHONE_SERVER_APPNAME', 'nahao');

//ok/error
define('SUCCESS', 'ok');
define('ERROR', 'error');

//过期时间
define('REDIS_VERIFY_CODE_EXPIRE_TIME', 3600); //测试暂定1小时，上线应该改为5分钟
define('REDIS_CAPTCHA_TIMES_EXPIRE_TIME', 90); //验证码次数有效期为90秒

//课程中的状态
define('NAHAO_STATUS_COURSE_INIT', 0); //未审核
define('NAHAO_STATUS_COURSE_DENY', 1); //审核未通过
define('NAHAO_STATUS_COURSE_RUNNING', 2); //审核通过，运营中
define('NAHAO_STATUS_COURSE_PAUSE', 3); //暂停
define('NAHAO_STATUS_COURSE_CLOSE', 4); //关闭

//验证码类型
define('REGISTER_VERIFY_CODE', 1);
define('BIND_VERIFY_CODE', 2);
define('GET_PASSWORD_VERIFY_CODE', 3);

//七牛账号
define('NH_QINIU_ACCESS_KEY', 'Wng4lDFFmffmt5A8QUgMnF_Z603W-6d3v60dyzoW');
define('NH_QINIU_SECRET_KEY', 'pnlEL16sISdWjJWRAKv5UaJqrfcK38lHee7B09b4');
define('NH_QINIU_BUCKET', 'nahaoweb');
define('NH_QINIU_VIDEO_BUCKET', 'nahaovideo');
define('NH_QINIU_RECORD_BUCKET', 'nahaorecord');
define('NH_QINIU_RECORD_URL', 'http://' . NH_QINIU_RECORD_BUCKET . '.qiniudn.com/');
define('NH_QINIU_VIDEO_URL', 'http://video1.nahao.com/');
define('NH_QINIU_URL', 'http://img1.nahao.com/');

define('MAX_NICKNAME_LEN', 25);

//meeting account
define('NH_MEETING_URL', ENVIRONMENT == 'production' ? 'http://classapi.tizi.com/' : 'http://classroom.oa.tizi.com/');
//define('NH_MEETING_URL', 'http://classapi.tizi.com/' );
define('NH_MEETING_ACCESS_KEY', '525510');
define('NH_MEETING_SECRET_KEY', '311ba4ffe6c74dd9af480d8411edc44e');

//define('NH_PDF_DOWNLOAD_URL',ENVIRONMENT=='production' ? 'http://nahao-pdf.qiniudn.com/' : NH_MEETING_URL.'media/');
define('NH_PDF_DOWNLOAD_URL', ENVIRONMENT == 'production' ? 'http://pdf.nahao.com/' : NH_MEETING_URL . 'media/');
//define('NH_PDF_DOWNLOAD_URL','http://nahao-pdf.qiniudn.com/');

//访问教室的用户类型
define('NH_MEETING_TYPE_STUDENT', 0); //学生
define('NH_MEETING_TYPE_TEACHER', 1); //老师
define('NH_MEETING_TYPE_ADMIN', 2); //管理员
define('NH_MEETING_TYPE_SYSTEM', 3); //系统，记录日志时用到
define('NH_MEETING_TYPE_SUPER_ADMIN', 110); //超级管理员
//进教室的链接，后面拼token就能进了
//define('NH_MEETING_ENTER_URL','http://classroom.oa.tizi.com/oa/enter?token=');
define('NH_MEETING_ENTER_URL', ENVIRONMENT == 'production' ? 'http://classapi.tizi.com/nahao/enter?token=' : 'http://classroom.oa.tizi.com/nahao/enter?token=');

//课程封面图的三个尺寸 288*216  230*172   50*50
define('NH_COURSE_IMG_LARGE_HEIGHT',216);
define('NH_COURSE_IMG_LARGE_WIDTH',288);
define('NH_COURSE_IMG_GENERAL_HEIGHT',172);
define('NH_COURSE_IMG_GENERAL_WIDTH',230);
define('NH_COURSE_IMG_SMALL_HEIGHT',50);
define('NH_COURSE_IMG_SMALL_WIDTH',50);
define('NH_COURSE_IMG_INDEX_HEIGHT',235);
define('NH_COURSE_IMG_INDEX_WIDTH',367);
define('NH_COURSE_IMG_LIVE_HEIGHT',147);
define('NH_COURSE_IMG_LIVE_WIDTH',230);
define('NH_TEACHER_IMG_HEIGHT', 225);
define('NH_TEACHER_IMG_WIDTH', 300);

//课程详细页
define('NH_BUY_BEFORE_TOP_BIG_IMG_HEIGHT', 280);
define('NH_BUY_BEFORE_TOP_BIG_IMG_WIDTH', 440);
define('NH_BUY_BEFORE_RIGHT_RECOMMEND_IMG_HEIGHT', 127);
define('NH_BUY_BEFORE_RIGHT_RECOMMEND_IMG_WIDTH', 200);
define('NH_RECENT_VIEW_IMG_HEIGHT', 51);
define('NH_RECENT_VIEW_IMG_WIDTH', 80);

//单轮，单节最大人数
define('NH_CLASS_PEOPLE_CAPS', 100);

//教龄上限
define('TEACHER_AGE_CEILING', 50);

//教室学生操作类型定义
define('CLASS_PLEASE_ACTION', 1); //赞
define('CLASS_SLOWER_ACTION', 2); //讲快一点
define('CLASS_FASTER_ACTION', 3); //讲慢一点
define('CLASS_BEGIN_ACTION', 4); //点上课
define('CLASS_OVER_ACTION', 5); //点下课

//轮用途类型
define('ROUND_USE_TYPE_NOMARL', 0); //正常轮
define('ROUND_USE_TYPE_TEST', 1); //测试轮
define('ROUND_USE_TYPE_APPLYTEACH', 2); //试讲轮

//教育类型
define('SUBJECT_STUDY', 1);//学科辅导
define('QUALITY_STUDY', 2);//素质教育

//列表页每页数量
define('LIST_NUM', 30);//每页搜索结果数
define('LIST_SUGGEST_NUM', 10);//每页推荐结果数

//存在redis里面的订单过期时间
define('REDIS_ORDER_EXPIRE', 3600);
define('ROUND_GENERATE_MODE','production');//testing,production测试环境下添课模式开关，testing走测试testing_round_time_config / production走正式production_round_time_config

//学科辅导和素质教育
define('ROUND_TYPE_ALL',0);
define('ROUND_TYPE_SUBJECT',1);
define('ROUND_TYPE_EDUCATION',2);

//所有学段
define('CATE_STAGE_ALL',0);//全部学段
define('CATE_STAGE_PRIMARY',1);//小学
define('CATE_STAGE_JUNIOR',2);//初中
define('CATE_STAGE_SENIOR',3);//高中

//学科辅导全部科目
define('CATE_SUBJECT_ALL',0);//全部科目
define('CATE_SUBJECT_SHUXUE',2);//数学
define('CATE_SUBJECT_YUWEN',3);//语文
define('CATE_SUBJECT_YINGYU',4);//英语
define('CATE_SUBJECT_WULI',5);//物理
define('CATE_SUBJECT_HUAXUE',9);//化学
define('CATE_SUBJECT_SHENGWU',10);//生物
define('CATE_SUBJECT_DILI',12);//地理
define('CATE_SUBJECT_ZHENGZHI',13);//政治
define('CATE_SUBJECT_SHUXUEJINGBIAN',14);//数学精编
define('CATE_SUBJECT_OTHER',100);//其他

//素质教育全部科目
define('CATE_QUALITY_ALL',0);//全部素质教育学科
define('CATE_QUALITY_JIATINGJIAOYU',1);//家庭教育
define('CATE_QUALITY_XUEXIFANGFA',2);//学习方法
define('CATE_QUALITY_WAIJIAOKOUYU',3);//外教口语
define('CATE_QUALITY_DIANYINGJIANSHANG',4);//电影鉴赏
define('CATE_QUALITY_XINGAINIAN',5);//新概念
define('CATE_QUALITY_GUOJIYINBIAO',6);//国际音标
define('CATE_QUALITY_XINLIXUE',7);//心理学
define('CATE_QUALITY_JIANQIAOSHAOERYINGYU',8);//剑桥少儿英语
define('CATE_QUALITY_ZIRANPINDU',9);//自然拼读
define('CATE_QUALITY_MOFANG',10);//魔方
define('CATE_QUALITY_OTHER',100);//其他


//switch
define('SWITCH_WWW_INDEX_LIVE_SHOW',1);
define('SWITCH_WWW_INDEX_COURSE_LIST',1);
//课程详情页推荐开关 (1是显示0是不显示)
define('BUY_BEFORE_RECOMMEND_SWITCH',1);

/* End of file constants.php */
/* Location: ./application/config/constants.php */
