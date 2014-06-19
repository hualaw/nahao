<?php
/**
 * sql_config
 * key按字母排序
 * @author yanrui@tizi.com
 */

//添加时按key字母排序
$config['sql_config'] = array(
    'admin' => array(
        TABLE_ADMIN => false
    ),
    'admin_group_permission' => array(
        TABLE_ADMIN => false,
        TABLE_ADMIN_PERMISSION_RELATION => array(TABLE_ADMIN . '.id=' . TABLE_ADMIN_PERMISSION_RELATION . '.admin_id', 'left'),
        TABLE_ADMIN_GROUP => array(TABLE_ADMIN_PERMISSION_RELATION . '.group_id=' . TABLE_ADMIN_GROUP . '.id', 'left')
    ),
    'area' => array(
        TABLE_NAHAO_AREAS => false
    ),
    'class' => array(
        TABLE_CLASS => false
    ),
    'course' => array(
        TABLE_COURSE => false
    ),
    'course_info' => array(
        TABLE_COURSE => false,
        TABLE_SUBJECT => array(TABLE_COURSE . '.subject=' . TABLE_SUBJECT . '.id', 'left'),
        TABLE_COURSE_TYPE => array(TABLE_COURSE . '.course_type=' . TABLE_COURSE_TYPE . '.id', 'left'),
        TABLE_COURSE_TEACHER_RELATION => array(TABLE_COURSE . '.id=' . TABLE_COURSE_TEACHER_RELATION . '.course_id', 'left'),
        TABLE_USER => array(TABLE_COURSE_TEACHER_RELATION . '.teacher_id=' . TABLE_USER . '.id', 'left'),
    ),
    'course_teachers' => array(
        TABLE_COURSE_TEACHER_RELATION => false,
        TABLE_USER => array(TABLE_COURSE_TEACHER_RELATION.'.teacher_id='.TABLE_USER.'.id','right'),
    ),
    'course_type' => array(
        TABLE_COURSE_TYPE => false,
    ),
    'group' => array(
        TABLE_ADMIN_GROUP => false
    ),
    'lesson' => array(
        TABLE_LESSON => false
    ),
    'round' => array(
        TABLE_ROUND => false
    ),
    'round_info' => array(
        TABLE_ROUND => false,
        TABLE_SUBJECT => array(TABLE_ROUND . '.subject=' . TABLE_SUBJECT . '.id', 'left'),
        TABLE_COURSE_TYPE => array(TABLE_ROUND . '.course_type=' . TABLE_COURSE_TYPE . '.id', 'left'),
        TABLE_ROUND_TEACHER_RELATION => array(TABLE_ROUND . '.id=' . TABLE_ROUND_TEACHER_RELATION . '.round_id', 'left'),
        TABLE_USER => array(TABLE_ROUND_TEACHER_RELATION . '.teacher_id=' . TABLE_USER . '.id', 'left'),
    ),
    'round_teachers' => array(
        TABLE_ROUND_TEACHER_RELATION => false,
        TABLE_USER => array(TABLE_ROUND_TEACHER_RELATION.'.teacher_id='.TABLE_USER.'.id','left'),
    ),
    'student_info' => array(
        TABLE_USER => false,
        TABLE_USER_INFO => array(TABLE_USER . '.id=' . TABLE_USER_INFO . '.user_id', 'left'),
        TABLE_NAHAO_AREAS => array(TABLE_USER_INFO . '.province=' . TABLE_NAHAO_AREAS . '.id', 'left'),
        TABLE_STUDENT_SUBJECT => array(TABLE_USER . '.id=' . TABLE_STUDENT_SUBJECT . '.student_id', 'left')
    ),
    'student_subject' => array(
        TABLE_STUDENT_SUBJECT => false,
    ),
    'subject' => array(
        TABLE_SUBJECT => false,
    ),
    'teacher_info' => array(
        TABLE_USER => false,
        TABLE_USER_INFO => array(TABLE_USER . '.id=' . TABLE_USER_INFO . '.user_id', 'left'),
        TABLE_NAHAO_AREAS => array(TABLE_USER_INFO . '.province=' . TABLE_NAHAO_AREAS . '.id', 'left'),
        TABLE_TEACHER_SUBJECT => array(TABLE_USER . '.id=' . TABLE_TEACHER_SUBJECT . '.teacher_id', 'left')
    ),
    'teacher_info_subject' => array(
        TABLE_USER => false,
        TABLE_USER_INFO => array(TABLE_USER . '.id=' . TABLE_USER_INFO . '.user_id', 'left'),
        TABLE_NAHAO_AREAS => array(TABLE_USER_INFO . '.province=' . TABLE_NAHAO_AREAS . '.id', 'left'),
        TABLE_TEACHER_SUBJECT => array(TABLE_USER . '.id=' . TABLE_TEACHER_SUBJECT . '.teacher_id', 'left'),
        TABLE_SUBJECT => array(TABLE_SUBJECT . '.id=' . TABLE_TEACHER_SUBJECT . '.subject_id', 'left')
    ),
    'teacher_subject' => array(
        TABLE_TEACHER_SUBJECT => false,
    ),
    'user' => array(
        TABLE_USER => false
    ),
    'user_info' => array(
        TABLE_USER => false,
        TABLE_USER_INFO => array(TABLE_USER . '.id=' . TABLE_USER_INFO . '.user_id', 'left'),
    ),
);
//添加时按key字母排序