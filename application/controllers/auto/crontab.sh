#安排菲律宾老师:每小时两跑,分别跑1小时后整点和半点的课
2,32 * * * * /usr/bin/php /space/waijiao/webroot/index.php arrange arrange_teacher 2

# 安排英美老师: 每小时两跑,分别跑24小时后整点和半点的课
1,30 * * * * /usr/bin/php /space/waijiao/webroot/index.php arrange arrange_teacher 1