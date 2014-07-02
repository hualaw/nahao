#安排菲律宾老师:每小时两跑,分别跑1小时后整点和半点的课
2,32 * * * * /usr/bin/php /space/waijiao/webroot/index.php arrange arrange_teacher 2

# 安排英美老师: 每小时两跑,分别跑24小时后整点和半点的课
1,30 * * * * /usr/bin/php /space/waijiao/webroot/index.php arrange arrange_teacher 1

#课，轮，课程平均分
1 0 */1 * * /usr/bin/php /opt/nahaotest.com/nahao/webroot/index.php Crontab score_counter 2

#学生订单从已取消到已关闭
* */1 * * * /usr/bin/php /opt/nahaotest.com/nahao/webroot/index.php Crontab order_status_setter 2

#每月结算老师的课酬
5 0 1 */1 * /usr/bin/php /opt/nahaotest.com/nahao/webroot/index.php Crontab teacher_checkout 2