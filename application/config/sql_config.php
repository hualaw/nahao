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
    'course_type' => array(
        TABLE_COURSE_TYPE => false,
    ),
    'group' => array(
        TABLE_ADMIN_GROUP => false
    ),
    'user' => array(
        TABLE_USER => false,
    ),
    'user_user_info'=> array(
        TABLE_USER => false,
        TABLE_USER_INFO => array(TABLE_USER.'.id='.TABLE_USER_INFO.'.user_id','left')
    ),
    'subject' => array(
        TABLE_SUBJECT => false,
    ),
);