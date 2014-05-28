<?php
/**
 * sql_config
 * @author yanrui@tizi.com
 */

$config['sql_admin']['admin'] = array(
    TABLE_ADMIN => false
);

$config['sql_admin']['admin_group_permission'] = array(
    TABLE_ADMIN => false,
    TABLE_ADMIN_PERMISSION_RELATION => array(TABLE_ADMIN . '.id=' . TABLE_ADMIN_PERMISSION_RELATION . '.admin_id', 'left'),
    TABLE_ADMIN_GROUP => array(TABLE_ADMIN_PERMISSION_RELATION . '.group_id=' . TABLE_ADMIN_GROUP . '.id', 'left')
);

$config['sql_admin']['group'] = array(
    TABLE_ADMIN_GROUP => false
);

$config['sql_admin']['user_user_info'] = array(
    TABLE_USER => false,
    TABLE_USER_INFO => array(TABLE_USER.'.id='.TABLE_USER_INFO.'.user_id','left')
);

$config['sql_admin']['user'] = array(
    TABLE_USER => false,
 );


$config['sql_www']['user'] = array(
TABLE_USER => false,
 );
