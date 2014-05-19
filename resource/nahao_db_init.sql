/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2014/5/19 10:25:59                           */
/*==============================================================*/


drop table if exists admin;

drop table if exists admin_group;

drop table if exists admin_permission_relation;

drop index idx_round_id on class;

drop table if exists class;

drop index idx_teacher_id on class_discuss_log;

drop table if exists class_discuss_log;

drop index idx_class_id on class_feedback;

drop table if exists class_feedback;

drop table if exists course;

drop table if exists course_grade_relation;

drop index idx_course_id on course_teacher_relation;

drop table if exists course_teacher_relation;

drop table if exists courseware;

drop table if exists entering_classroom;

drop index idx_group_id on group_permission_relation;

drop table if exists group_permission_relation;

drop index idx_course_id on lesson;

drop table if exists lesson;

drop index idx_order_id on order_round_relation;

drop table if exists order_round_relation;

drop table if exists permission;

drop table if exists question;

drop index idx_class_id on question_class_relation;

drop table if exists question_class_relation;

drop index idx_lesson_id on question_lesson_relation;

drop table if exists question_lesson_relation;

drop index idx_course_id on round;

drop table if exists round;

drop index idx_round_id on round_note;

drop table if exists round_note;

drop index idx_round_id on round_teacher_relation;

drop table if exists round_teacher_relation;

drop index idx_student on shopping_cart;

drop table if exists shopping_cart;

drop index idx_phone on sms_log;

drop table if exists sms_log;

drop index idx_phone on sms_verify_code;

drop table if exists sms_verify_code;

drop index idx_student_id on student_class;

drop table if exists student_class;

drop table if exists student_class_log;

drop table if exists student_info;

drop index idx_student_id on student_order;

drop table if exists student_order;

drop table if exists student_refund;

drop table if exists student_subject;

drop index idx_class_id on sutdent_question;

drop table if exists sutdent_question;

drop index idx_teacher_id on teacher_checkout_log;

drop table if exists teacher_checkout_log;

drop table if exists teacher_info;

drop table if exists teacher_lectrue;

drop table if exists teacher_subject;

drop index idx_nickname on user;

drop index idx_email on user;

drop table if exists user;

/*==============================================================*/
/* Table: admin                                                 */
/*==============================================================*/
create table admin
(
   id                   int(10) not null auto_increment,
   nickname             varchar(20),
   phone                char(11),
   email                varchar(90),
   salt                 char(6),
   password             char(40),
   realname             varchar(90),
   register_time        int(10),
   register_ip          int(10),
   status               tinyint(3) comment '0,1',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: admin_group                                           */
/*==============================================================*/
create table admin_group
(
   id                   int(10) not null auto_increment,
   name                 varchar(90),
   status               tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: admin_permission_relation                             */
/*==============================================================*/
create table admin_permission_relation
(
   admin_id             int(10),
   group_id             int(10),
   permission_id        int(10)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: class                                                 */
/*==============================================================*/
create table class
(
   id                   int(10) not null auto_increment,
   course_id            int(10),
   round_id             int(10),
   lesson_id            int(10),
   title                varchar(90),
   begin_time           int(10),
   end_time             int(10),
   courseware_id        int(10),
   sequence             int(10),
   status               tinyint(3) comment '0禁用;1启用',
   parent_id            char(10),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table class comment '课（课节的实例） 默认一课是两课时';

/*==============================================================*/
/* Index: idx_round_id                                          */
/*==============================================================*/
create index idx_round_id on class
(
   round_id
);

/*==============================================================*/
/* Table: class_discuss_log                                     */
/*==============================================================*/
create table class_discuss_log
(
   id                   int(10) not null auto_increment,
   uid                  int(10) not null default 0,
   role                 tinyint(3) not null default 0 comment '1,学生；2，老师；3，管理员',
   nickname             varchar(30) not null default '0' comment '用户头像',
   message              varchar(210) comment '用户说的话',
   msg_create_time      int(10) not null default 0 comment '消息创建时间',
   create_time          int(10) comment '入库时间',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_teacher_id                                        */
/*==============================================================*/
create index idx_teacher_id on class_discuss_log
(
   uid
);

/*==============================================================*/
/* Table: class_feedback                                        */
/*==============================================================*/
create table class_feedback
(
   id                   int(10) not null auto_increment,
   course_id            int(10),
   round_id             int(10),
   class_id             int(10),
   student_id           int(10),
   nickname             varchar(90),
   content              text,
   create_time          int(10),
   score                decimal(10,2),
   is_show              tinyint(1),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_class_id                                          */
/*==============================================================*/
create index idx_class_id on class_feedback
(
   class_id
);

/*==============================================================*/
/* Table: course                                                */
/*==============================================================*/
create table course
(
   id                   int(10) not null auto_increment,
   title                varchar(90),
   subtitle             varchar(150),
   intro                varchar(900),
   description          text,
   students             varchar(300) comment '适合人群',
   subject              int(10) comment '科目',
   course_type          tinyint(3) comment '课程类型',
   reward               decimal(10,2) comment '本课程每课时（45分钟，计算时乘2）给老师团队的报酬',
   price                decimal(10,2) comment '价格',
   status               tinyint(3) not null default 0 comment '0初始化;5审核中;10运营中;15暂停;20关闭;25',
   create_time          int(10) not null default 0,
   role                 int(10),
   user_id              int(10) not null default 0 comment '创建人',
   score                decimal(10,2) not null default 0 comment '由每个课时的评分取平均数',
   bought_count         int(10) comment '购买人数',
   graduate_count       int(10) comment '结课人数',
   video                varchar(255) comment '视频地址',
   img                  varchar(255) comment '封面原图地址',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: course_grade_relation                                 */
/*==============================================================*/
create table course_grade_relation
(
   id                   int(10) not null auto_increment,
   course_id            int(10),
   grade                tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table course_grade_relation comment '课程与年级关系（多对多互查）';

/*==============================================================*/
/* Table: course_teacher_relation                               */
/*==============================================================*/
create table course_teacher_relation
(
   id                   int(10) not null auto_increment,
   course_id            int(10),
   teacher_id           int(10),
   role                 int(10),
   sequence             tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_course_id                                         */
/*==============================================================*/
create index idx_course_id on course_teacher_relation
(
   course_id
);

/*==============================================================*/
/* Table: courseware                                            */
/*==============================================================*/
create table courseware
(
   id                   int(10) not null auto_increment,
   name                 varchar(90),
   create_time          varchar(10),
   status               tinyint(3) comment '0启用;5禁用',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: entering_classroom                                    */
/*==============================================================*/
create table entering_classroom
(
   id                   int(10) not null auto_increment,
   user_id              int(10),
   create_time          int(10) comment '选择题答案，可能一项 多项用逗号分割',
   action               tinyint(3) comment '1, 进入；2，退出',
   ip                   bigint(10),
   class_id             int(10),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: group_permission_relation                             */
/*==============================================================*/
create table group_permission_relation
(
   group_id             int(10),
   permission_id        int(10)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_group_id                                          */
/*==============================================================*/
create index idx_group_id on group_permission_relation
(
   group_id
);

/*==============================================================*/
/* Table: lesson                                                */
/*==============================================================*/
create table lesson
(
   id                   int(10) not null auto_increment,
   course_id            int(10),
   title                varchar(90),
   courseware_id        int(10),
   status               tinyint(3) comment '0禁用;1启用',
   parent_id            int(10),
   sequence             tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_course_id                                         */
/*==============================================================*/
create index idx_course_id on lesson
(
   course_id
);

/*==============================================================*/
/* Table: order_round_relation                                  */
/*==============================================================*/
create table order_round_relation
(
   id                   int(10) not null auto_increment,
   order_id             int(10) not null,
   round_id             int(10) not null,
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_order_id                                          */
/*==============================================================*/
create index idx_order_id on order_round_relation
(
   order_id
);

/*==============================================================*/
/* Table: permission                                            */
/*==============================================================*/
create table permission
(
   id                   int(10) not null auto_increment,
   name                 varchar(30),
   controller           varchar(10),
   action               varchar(10),
   status               tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: question                                              */
/*==============================================================*/
create table question
(
   id                   int(10) not null auto_increment,
   question             text,
   answer               varchar(10) comment '选择题答案，可能一项 多项用逗号分割',
   options              varchar(1000) comment '选项json',
   type                 tinyint(3) comment '单选多选',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: question_class_relation                               */
/*==============================================================*/
create table question_class_relation
(
   id                   int(10) not null auto_increment,
   class_id             int(10),
   question_id          int(10),
   status               tinyint(3) comment '0没出过；1出过',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_class_id                                          */
/*==============================================================*/
create index idx_class_id on question_class_relation
(
   class_id
);

/*==============================================================*/
/* Table: question_lesson_relation                              */
/*==============================================================*/
create table question_lesson_relation
(
   id                   int(10) not null auto_increment,
   lesson_id            int(10),
   question_id          int(10),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_lesson_id                                         */
/*==============================================================*/
create index idx_lesson_id on question_lesson_relation
(
   lesson_id
);

/*==============================================================*/
/* Table: round                                                 */
/*==============================================================*/
create table round
(
   id                   int(10) not null auto_increment,
   course_id            int(10),
   title                varchar(90),
   subtitle             varchar(150) comment '一句话简介',
   intro                varchar(900),
   description          text,
   students             varchar(300) comment '适合人群',
   subject              int(10) comment '科目',
   course_type          tinyint(3) comment '课程类型',
   reward               decimal(10,2) comment '本课程每课时（45分钟，计算时乘2）给老师团队的报酬',
   price                decimal(10,2) not null default 0,
   sale_status          tinyint(3) not null default 0 comment '销售状态（未审核、审核不通过、审核通过、预售、销售中、已售罄、已停售（时间到了还没售罄）、已下架（手动下架））',
   create_time          int(10) not null default 0,
   role                 int(10),
   user_id              int(10) not null default 0 comment '创建人',
   score                decimal(10,2) not null default 0,
   bought_count         int(10) not null default 0 comment '已购买人数',
   caps                 int(10) comment '上限人数 默认100',
   sale_price           decimal(10,2) not null default 0,
   sell_begin_time      int(10),
   sell_end_time        int(10),
   start_time           int(10) comment '前台显示2014-5-16格式',
   end_time             int(10) comment '前台显示2014-5-16格式',
   video                varchar(255) comment '视频地址',
   img                  varchar(255) comment '封面原图地址',
   teach_status         tinyint(3) comment '授课状态(等待开课、授课中、停课（手动操作）、结课)',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table round comment '轮（课程的实例）';

/*==============================================================*/
/* Index: idx_course_id                                         */
/*==============================================================*/
create index idx_course_id on round
(
   course_id
);

/*==============================================================*/
/* Table: round_note                                            */
/*==============================================================*/
create table round_note
(
   id                   int(10) not null auto_increment,
   round_id             int(10) not null default 0,
   author               int(10) not null default 0,
   author_role          tinyint(3) comment '-1，管理员；1,主讲；2，助教，',
   content              varchar(600),
   create_time          int(10) not null default 0,
   status               tinyint(3) not null default 0 comment '1，未审核；2，审核不通过；3，审核通过',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_round_id                                          */
/*==============================================================*/
create index idx_round_id on round_note
(
   round_id
);

/*==============================================================*/
/* Table: round_teacher_relation                                */
/*==============================================================*/
create table round_teacher_relation
(
   id                   int(10) not null auto_increment,
   round_id             int(10),
   teacher_id           int(10),
   role                 int(10),
   sequence             tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_round_id                                          */
/*==============================================================*/
create index idx_round_id on round_teacher_relation
(
   round_id
);

/*==============================================================*/
/* Table: shopping_cart                                         */
/*==============================================================*/
create table shopping_cart
(
   id                   int(10) not null auto_increment,
   student_id           int(10),
   round_id             int(10),
   round_title          varchar(90),
   create_time          int(10),
   status               tinyint(1) comment '0，未删除；1，已删除',
   teach_period         varchar(80) comment '上课时间区间',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_student                                           */
/*==============================================================*/
create index idx_student on shopping_cart
(
   student_id
);

/*==============================================================*/
/* Table: sms_log                                               */
/*==============================================================*/
create table sms_log
(
   id                   int(10) not null auto_increment,
   phone                char(11),
   content              varchar(210),
   create_time          int(10),
   status               tinyint(1) comment '0,1',
   user_id              int(10),
   type                 tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_phone                                             */
/*==============================================================*/
create index idx_phone on sms_log
(
   phone
);

/*==============================================================*/
/* Table: sms_verify_code                                       */
/*==============================================================*/
create table sms_verify_code
(
   id                   int(10) not null auto_increment,
   phone                char(11),
   verify_code          int(6),
   create_time          int(10),
   deadline             int(10),
   ip                   bigint(10),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_phone                                             */
/*==============================================================*/
create index idx_phone on sms_verify_code
(
   phone
);

/*==============================================================*/
/* Table: student_class                                         */
/*==============================================================*/
create table student_class
(
   id                   int(10) not null auto_increment,
   student_id           int(10) not null default 0,
   course_id            int(10) not null default 0,
   round_id             int(10) not null default 0,
   class_id             int(10) not null default 0,
   status               tinyint(3) not null default 0 comment '0，初始化（未到上课时间）1，缺席（进入教师按钮可用时间段内，学生没点过此按钮算缺席）；2，进过教师；3，申请退款；4，退款不通过；5，退款通过；6，退款已完成',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table student_class comment '学生与课关系表（余额）';

/*==============================================================*/
/* Index: idx_student_id                                        */
/*==============================================================*/
create index idx_student_id on student_class
(
   student_id
);

/*==============================================================*/
/* Table: student_class_log                                     */
/*==============================================================*/
create table student_class_log
(
   id                   int(10) not null auto_increment,
   student_id           int(10) not null default 0,
   class_id             int(10) not null default 0,
   action               tinyint(3) not null default 0 comment '举手献花等动作 待定',
   create_time          int(10) not null,
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table student_class_log comment '记录学生在课堂上所有动作';

/*==============================================================*/
/* Table: student_info                                          */
/*==============================================================*/
create table student_info
(
   user_id              int(10) not null,
   realname             varchar(90),
   age                  tinyint(3),
   gender               tinyint(1),
   grade                tinyint(3),
   province             tinyint(1),
   city                 tinyint(1),
   area                 tinyint(1),
   school               tinyint(1),
   primary key (user_id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: student_order                                         */
/*==============================================================*/
create table student_order
(
   id                   int(10) not null auto_increment,
   student_id           int(10) not null default 0,
   create_time          int(10) not null default 0,
   confirm_time         int(10),
   pay_type             tinyint(3) comment '1，网银；2，信用卡；3，支付宝',
   price                decimal(10,2) comment '原价',
   spend                decimal(10,2) comment '实际支付价格',
   status               tinyint(3) not null default 0 comment '1，未付款；
            2，已付款；
            3，已完成（付款完成后7天自动变成这个状态，暂时用不上）；
            4，已取消（用户主动取消）
            5，已关闭（订单超时，系统自动关闭）',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_student_id                                        */
/*==============================================================*/
create index idx_student_id on student_order
(
   student_id
);

/*==============================================================*/
/* Table: student_refund                                        */
/*==============================================================*/
create table student_refund
(
   id                   int(10) not null auto_increment,
   round_id             int(10) not null default 0,
   student_id           int(10) not null default 0,
   times                tinyint(3) not null default 0 comment '可退课数',
   amount               decimal(10,2) not null default 0 comment '退款总金额',
   admin_id             int(10) default 0 comment '审核人ID',
   status               tinyint(3) not null default 0 comment '1, 处理中；2，退款成功；3，退款失败',
   create_time          int(10) not null default 0,
   confirm_time         int(10),
   order_id             int(10),
   reason               varchar(150),
   comment              varchar(150) comment '1，退款成功；2，退款失败理由',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table student_refund comment '学生的退款记录';

/*==============================================================*/
/* Table: student_subject                                       */
/*==============================================================*/
create table student_subject
(
   student_id           int(10),
   subject_id           int(10)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table student_subject comment '学生感兴趣的科目表';

/*==============================================================*/
/* Table: sutdent_question                                      */
/*==============================================================*/
create table sutdent_question
(
   id                   int(10) not null auto_increment,
   class_id             int(10),
   student_id           int(10),
   question_id          int(10),
   answer               varchar(90),
   is_correct           tinyint(1),
   sequence             tinyint(3) comment '第几次答题',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_class_id                                          */
/*==============================================================*/
create index idx_class_id on sutdent_question
(
   class_id
);

/*==============================================================*/
/* Table: teacher_checkout_log                                  */
/*==============================================================*/
create table teacher_checkout_log
(
   id                   int(10) not null auto_increment,
   teacher_id           int(10) not null default 0,
   status               tinyint(3) not null default 0 comment '0结算完成;5老师确认;10取消;15',
   teach_times          int(10),
   class_times          int(10),
   gross_income         int(10) not null default 0 comment '总课时费',
   net_income           int(10) comment '税后收入=（总课时费 - 额外扣除 - 税费）',
   deduct               int(10),
   tax                  int(10),
   create_time          int(10) comment '记录产生时间',
   pay_time             int(10) not null default 0 comment '付款时间',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table teacher_checkout_log comment '给老师结算的记录表 每个老师一个月一条记录';

/*==============================================================*/
/* Index: idx_teacher_id                                        */
/*==============================================================*/
create index idx_teacher_id on teacher_checkout_log
(
   teacher_id
);

/*==============================================================*/
/* Table: teacher_info                                          */
/*==============================================================*/
create table teacher_info
(
   user_id              int(10) not null comment '用户id',
   realname             varchar(30),
   age                  tinyint(3),
   gender               tinyint(1),
   hide_realname        tinyint(1),
   hide_school          tinyint(1),
   hide_area            tinyint(1),
   bankname             varchar(90),
   bankbench            varchar(150),
   bankcard             varchar(20),
   id_code              char(18),
   title                tinyint(3),
   work_auth            tinyint(1),
   teacher_auth         tinyint(1),
   titile_auth          tinyint(1),
   province             tinyint(1),
   city                 tinyint(1),
   area                 tinyint(1),
   school               tinyint(1),
   remuneration         decimal(10,2) comment '已经领过的总课酬',
   teacher_age          tinyint(3),
   stage                tinyint(3),
   primary key (user_id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: teacher_lectrue                                       */
/*==============================================================*/
create table teacher_lectrue
(
   id                   int(10) not null auto_increment,
   course               varchar(90) not null comment '名称',
   resume               text comment '介绍',
   subject              varchar(90) comment '试讲科目',
   status               tinyint(3) not null default 0 comment '1,未审核；2，审核未通过；3，审核通过',
   admin_id             int(10) not null default 0 comment '审核人id',
   create_time          int(10) not null default 0 comment '创建试讲信息的时间',
   province             int(10),
   city                 int(10),
   area                 int(10),
   school               int(10),
   period               tinyint(1),
   teach_years          tinyint(3),
   course_intro         text,
   teach_type           tinyint(3),
   gender               tinyint(1),
   title                tinyint(3),
   age                  int(3),
   phone                char(11),
   email                varchar(40),
   qq                   varchar(11),
   start_time           int(10) comment '试讲开始时间',
   end_time             int(10) comment '试讲结束时间',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table teacher_lectrue comment '老师的试讲信息表';

/*==============================================================*/
/* Table: teacher_subject                                       */
/*==============================================================*/
create table teacher_subject
(
   teacher_id           int(10),
   subject_id           int(10)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table teacher_subject comment '老师能教的科目表';

/*==============================================================*/
/* Table: user                                                  */
/*==============================================================*/
create table user
(
   id                   int(10) not null auto_increment,
   nickname             varchar(30),
   phone_mask           char(11),
   email                varchar(90),
   salt                 char(6),
   password             char(40),
   status               tinyint(3) comment '0,1',
   role                 tinyint(3),
   register_time        int(10),
   register_ip          bigint(10),
   source               tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_email                                             */
/*==============================================================*/
create index idx_email on user
(
   email
);

/*==============================================================*/
/* Index: idx_nickname                                          */
/*==============================================================*/
create unique index idx_nickname on user
(
   nickname
);

