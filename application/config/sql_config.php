<?php
/**
 * sql_config
 * @author yanrui@tizi.com
 */

$config['sql_admin'] = array(
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
    'course_info' => array(
        TABLE_COURSE => false,
        TABLE_SUBJECT => array(TABLE_COURSE.'.subject='.TABLE_SUBJECT.'.id','left'),
        TABLE_COURSE_TYPE => array(TABLE_COURSE.'.course_type='.TABLE_COURSE_TYPE.'.id','left'),
        TABLE_COURSE_TEACHER_RELATION => array(TABLE_COURSE.'.id='.TABLE_COURSE_TEACHER_RELATION.'.course_id','left'),
        TABLE_USER => array(TABLE_COURSE_TEACHER_RELATION.'.teacher_id='.TABLE_USER.'.nickname','left'),
    ),
    'course_type' => array(
        TABLE_COURSE_TYPE => false,
    ),
    'group' => array(
        TABLE_ADMIN_GROUP => false
    ),
    'student_info'=> array(
        TABLE_USER => false,
        TABLE_USER_INFO => array(TABLE_USER.'.id='.TABLE_USER_INFO.'.user_id','left'),
        TABLE_NAHAO_AREAS => array(TABLE_USER_INFO.'.province='.TABLE_NAHAO_AREAS.'.id','left'),
        TABLE_STUDENT_SUBJECT => array(TABLE_USER.'.id='.TABLE_STUDENT_SUBJECT.'.student_id','left')
    ),
    'subject' => array(
        TABLE_SUBJECT => false,
    ),
);