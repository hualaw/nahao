/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2014/5/12 18:08:33                           */
/*==============================================================*/


drop table if exists admin;

drop table if exists admin_group;

drop table if exists admin_permission_relation;

drop index idx_round_id on class;

drop table if exists class;

drop index idx_class_id on class_feedback;

drop table if exists class_feedback;

drop table if exists course;

drop index idx_course_id on course_teacher_relation;

drop table if exists course_teacher_relation;

drop table if exists courseware;

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

drop index idx_email on student;

drop table if exists student;

drop index idx_student_id on student_class;

drop table if exists student_class;

drop table if exists student_class_log;

drop table if exists student_info;

drop index idx_student_id on student_order;

drop table if exists student_order;

drop table if exists student_refund;

drop table if exists student_subject;

drop table if exists subject;

drop index idx_class_id on sutdent_question;

drop table if exists sutdent_question;

drop index idex_email on teacher;

drop table if exists teacher;

drop index idx_teacher_id on teacher_checkout;

drop table if exists teacher_checkout;

drop table if exists teacher_info;

drop index idx_teacher_id on teacher_lectrue;

drop table if exists teacher_lectrue;

drop table if exists teacher_subject;

/*==============================================================*/
/* Table: admin                                                 */
/*==============================================================*/
create table admin
(
   id                   int(10) not null auto_increment,
   nickname             varchar(90),
   phone                char(11),
   email                varchar(90),
   salt                 char(6),
   password             char(90),
   realname             varchar(90),
   register_time        int(10),
   register_ip          int(10),
   source               tinyint(3),
   status               tinyint(3),
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
   content              varchar(180),
   begin_time           int(10),
   courseware_id        int(10),
   sequence             int(10),
   is_chapter           tinyint(3),
   status               tinyint(3) comment '0未上课;5上课中;10课程结束;15已取消',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_round_id                                          */
/*==============================================================*/
create index idx_round_id on class
(
   round_id
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
   subtitle             varchar(90),
   intro                varchar(180),
   description          text,
   grade                varchar(90) comment '适合学生',
   status               tinyint(3) not null default 0 comment '0初始化;5审核中;10运营中;15暂停;20关闭;25',
   create_time          int(10) not null default 0,
   admin_id             int(10) not null default 0 comment '创建人',
   score                decimal(10,2) not null default 0 comment '由每个课时的评分取平均数',
   bought_count         int(10) comment '购买人数',
   graduate_count       int(10) comment '结课人数',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Table: course_teacher_relation                               */
/*==============================================================*/
create table course_teacher_relation
(
   id                   int(10) not null auto_increment,
   course_id            int(10),
   teacher_id           int(10),
   role                 int(10),
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
/* Table: lesson                                                */
/*==============================================================*/
create table lesson
(
   id                   int(10) not null auto_increment,
   course_id            int(10),
   title                varchar(90),
   content              varchar(180),
   courseware_id        int(10),
   sequence             int(10),
   is_chapter           tinyint(3) comment '0不是1是',
   status               tinyint(3) comment '0启用;5禁用',
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
   name                 varchar(90),
   controller           varchar(90),
   action               varchar(90),
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
   question             text comment '选项也在题目中存储',
   answer               varchar(90) comment '可能是选择 可能是填空',
   type                 tinyint(3) comment '0选择;5填空等',
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
   sequence             int(10),
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
   sequence             int(10),
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
   subtitle             varchar(90),
   intro                varchar(180),
   arrange_intro        varchar(180),
   description          text,
   students             varchar(90) comment '适合学生',
   score                decimal(10,2) not null default 0,
   bought_count         int(10) not null default 0,
   status               tinyint(3) not null default 0 comment '0初始化;5审核中;10运营中;15暂停;20关闭;',
   price                decimal(10,2) not null default 0,
   sell_begin_time      int(10),
   sell_end_time        int(10),
   start_time           int(10),
   end_time             int(10),
   sale_price           decimal(10,2) not null default 0,
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
   author_role          tinyint(3),
   content              varchar(180),
   create_time          int(10) not null default 0,
   status               tinyint(3) not null default 0 comment '0初始化;5发布;10取消;15',
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
   status               tinyint(1),
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
   content              varchar(255),
   create_time          int(10),
   tips                 varchar(255),
   status               tinyint(1),
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
   id                   int not null auto_increment,
   phone                char(11),
   verify_code          char(6),
   create_time          int(10),
   deadline             int(10),
   ip                   int(10),
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
/* Table: student                                               */
/*==============================================================*/
create table student
(
   id                   int(10) not null auto_increment,
   nickname             varchar(90),
   phone                char(11),
   email                varchar(90),
   salt                 char(6),
   password             char(90),
   status               tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idx_email                                             */
/*==============================================================*/
create index idx_email on student
(
   email
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
   status               tinyint(3) not null default 0 comment '0初始化;5上课没出席;上课并出席10;15',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table student_class comment '学生与课时关系表（余额）';

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
   student_id           int(10) not null,
   realname             varchar(90),
   age                  tinyint(3),
   gender               tinyint(1),
   level                tinyint(3),
   register_time        int(10),
   register_ip          int(10),
   source               tinyint(3),
   grade                varchar(90),
   province             varchar(90),
   city                 varchar(90),
   area                 varchar(90),
   school               varchar(90),
   primary key (student_id)
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
   pay_type             tinyint(3),
   price                decimal(10,2) comment '价格',
   spend                decimal(10,2) comment '实际支付价格',
   status               tinyint(3) not null default 0 comment '0初始化;5已付款;10付款成功7天;15取消;20',
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
   times                int(5) not null default 0 comment '退课时数',
   amount               decimal(10,2) not null default 0 comment '退款总金额',
   operator             int(10) default 0 comment '操作人',
   status               tinyint(3) not null default 0 comment '0初始化;5提交审核;10审核通过;15审核不通过;20取消',
   create_time          int(10) not null default 0,
   confirm_time         int(10),
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
/* Table: subject                                               */
/*==============================================================*/
create table subject
(
   id                   int(10) not null auto_increment,
   name                 varchar(90),
   intro                varchar(90),
   description          varchar(180),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

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
/* Table: teacher                                               */
/*==============================================================*/
create table teacher
(
   id                   int(10) not null auto_increment,
   nickname             varchar(90),
   phone                char(11),
   email                varchar(90),
   salt                 char(6),
   password             char(90),
   status               tinyint(3),
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

/*==============================================================*/
/* Index: idex_email                                            */
/*==============================================================*/
create index idex_email on teacher
(
   email
);

/*==============================================================*/
/* Table: teacher_checkout                                      */
/*==============================================================*/
create table teacher_checkout
(
   id                   int(10) not null auto_increment,
   teacher_id           int(10) not null default 0,
   create_time          int(10) not null default 0 comment '结算时间',
   amount               decimal(10,2) not null default 0 comment '结算金额',
   status               tinyint(3) not null default 0 comment '0结算完成;5老师确认;10取消;15',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table teacher_checkout comment '给老师结算的记录表 每个老师一个月一条记录';

/*==============================================================*/
/* Index: idx_teacher_id                                        */
/*==============================================================*/
create index idx_teacher_id on teacher_checkout
(
   teacher_id
);

/*==============================================================*/
/* Table: teacher_info                                          */
/*==============================================================*/
create table teacher_info
(
   teacher_id           int(10) not null,
   realname             varchar(90),
   age                  tinyint(3),
   gender               tinyint(1),
   level                tinyint(3),
   hide_realname        tinyint(1),
   hide_school          tinyint(1),
   hide_area            tinyint(1),
   register_time        int(10),
   register_ip          int(10),
   source               tinyint(3),
   bankname             varchar(90),
   bankbench            varchar(90),
   bankcard             varchar(20),
   id_code              char(18),
   title                varchar(90),
   id_auth              tinyint(1),
   teacher_auth         tinyint(1),
   titile_auth          tinyint(1),
   school_auth          tinyint(1),
   province             varchar(90),
   city                 varchar(90),
   area                 varchar(90),
   school               varchar(90),
   remuneration         decimal(10,3) comment '已经领过的总课酬',
   teacher_age          tinyint(3),
   stage                tinyint(3),
   primary key (teacher_id)
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
   teacher_id           int(10) not null comment '老师id',
   title                varchar(90) not null comment '名称',
   content              text comment '介绍',
   subject              varchar(90) comment '科目',
   status               tinyint(3) not null default 0 comment '0初始化;5提交审核;10审核通过;15审核不通过;20取消',
   admin_id             int(10) not null default 0 comment '操作人id',
   create_time          int(10) not null default 0 comment '创建时间',
   start_time           int(10) comment '开课时间',
   primary key (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

alter table teacher_lectrue comment '老师的试讲信息表';

/*==============================================================*/
/* Index: idx_teacher_id                                        */
/*==============================================================*/
create index idx_teacher_id on teacher_lectrue
(
   teacher_id
);

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

