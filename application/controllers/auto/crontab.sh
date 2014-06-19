#开课前24小时排24小时时间点的预约课程，会排整点和半点的约课

#1,31 * * * * /usr/bin/php /space/waijiao/webroot/index.php arrange index

#安排菲律宾老师:每小时两跑,分别跑1小时后整点和半点的课
2,32 * * * * /usr/bin/php /space/waijiao/webroot/index.php arrange arrange_teacher 2

# 安排英美老师: 每小时两跑,分别跑24小时后整点和半点的课
1,30 * * * * /usr/bin/php /space/waijiao/webroot/index.php arrange arrange_teacher 1

#新概念排课
2 1 * * * /usr/bin/php /space/waijiao/webroot/index.php arrange nec_class >> /tmp/arrange_nec_class.log 2>&1

#更新权限
* */1 * * * /usr/bin/php /space/waijiao/webroot/index.php crm sales >> /tmp/nohup 2>&1

#给特殊学生发邮件
1 20 * * * /usr/bin/php /space/waijiao/webroot/index.php course special_student_email >> /tmp/nohup 2>&1

#周五 老师 提醒 邮件
1 22 * * 5 /usr/bin/php /space/waijiao/webroot/index.php course email_notifier_for_teacher

#体验课排课
#3 2 * * * /usr/bin/php /space/waijiao/webroot/index.php arrange experience_class
3 1 * * * /usr/bin/php /space/waijiao/webroot/index.php arrange new_experience_class


#通知，目前都是1小时1跑，课堂开始前2小时，发短信通知学生
2 * * * * /usr/bin/php /space/waijiao/webroot/index.php course sms_notice_student >> /tmp/nohup 2>&1

#设置可上课时间，每天跑一次，把未来1-15天下面逻辑处理好，1天处理一次，英美7、8、9、10、11、12、19、20、21、22、23，
#非英美12，13，14，15，16，17，18，19，20，21，22，定级课9，10，11，12，20，21，22，23
3 0 * * * /usr/bin/php /space/waijiao/webroot/index.php course save_class_time >> /tmp/nohup 2>&1

#开课前12小时候时候老师取消或者没确认或者没手工排上课的，给之前老师发邮件，他的资格这节课取消了，系统再排一次，
#没有老师发到warning，有老师的话发邮件发短信，提前2个小时老师取消或者没确认或者没手工排上课的，再给warning，给老师发邮件他的资格这节课取消了
2 * * * * /usr/bin/php /space/waijiao/webroot/index.php arrange class_find_teacher_again >> /tmp/nohup 2>&1

#清理短信验证数据，清理过去24小时的以前的，就是说只保留最近24小时的
5 0 * * * /usr/bin/php /space/waijiao/webroot/index.php course clear_sms_verify >> /tmp/nohup 2>&1

#每分钟运行一次 变更课堂状态(上课中,授课完成, 给学生添加上课记录, 给教师统计报酬)
*/1 * * * * /usr/bin/php /space/waijiao/webroot/index.php cron_class main >> /tmp/nohup 2>&1

#订单状态更改(过期未支付)
*/5 * * * * /usr/bin/php /space/waijiao/webroot/index.php cron_order main >> /tmp/nohup 2>&1

#试听课余额用尽短信通知用户
*/5 * * * * /usr/bin/php /space/waijiao/webroot/index.php cron_demo_class_deplenish main >> /tmp/nohup 2>&1

#临时监控
*/10 * * * * /usr/bin/php /space/waijiao/webroot/index.php cron_tmp monitor >> /tmp/nohup 2>&1

#订单过期短信提醒(每天晚上8点运行)
0 20 * * * /usr/bin/php /space/waijiao/webroot/index.php cron_order_remind_expired main >> /tmp/nohup 2>&1

#给老师发短信列表运行
*/1 * * * * /usr/bin/php /space/waijiao/webroot/index.php course action_sms_query >> /tmp/nohup 2>&1

#每分钟执行守护进程检查程序 如果检查程序发现class_online_server挂了，就重启。
#* * * * *  nohup /usr/bin/php /space/waijiao/webroot/index.php check_class_online_daemon check >> /tmp/class_online_daemon.log 2>&1 &
* * * * *  nohup /bin/bash /space/waijiao/application/controllers/auto/check_class_online_daemon.sh >> /tmp/class_online_daemon.log 2>&1 &

#给class_online表中轮换课堂信息
* * * * * /usr/bin/php /space/waijiao/webroot/index.php online update >> /tmp/class_online_update.log 2>&1


#删除class_online表中小于等于昨天的数据
0 1 * * * /usr/bin/php /space/waijiao/webroot/index.php online del

#日志归档
2 4 * * * /usr/bin/php /space/waijiao/webroot/index.php archive_log archive  >> /tmp/archive.log 2>&1

#thrift探针
*/1 * * * * /usr/bin/php /space/waijiao/application/controllers/auto/test_flashserver2php.php

#每日 活动 统计
5 0 * * *　/usr/bin/php /space/waijiao/webroot/index.php cron_activity activity_stats >>/tmp/activity_stats.log 2>&1

# 活动状态 更新
5 0 * * *　/usr/bin/php /space/waijiao/webroot/index.php cron_activity update_activity_valid >>/tmp/activity_status.log 2>&1

#数据统计
*/15 * * * *  /usr/bin/php /space/waijiao/webroot/index.php cron_statics main >>/tmp/cron_statics.log 2>&1

#短信队列
*/1 * * * * /usr/bin/php /space/waijiao/webroot/index.php cron_sms sms_send >>/tmp/sms_send.log 2>&1
#邮件队列
*/5 * * * * /usr/bin/php /space/waijiao/webroot/index.php cron_mail mail_send >>/tmp/mail_send.log 2>&1

#自动运行 老师结算
10 0 * * * /usr/bin/php /space/waijiao/webroot/index.php cron_class auto__sys_stat_topay >> /tmp/auto__sys_stat_topay.log 2>&1
