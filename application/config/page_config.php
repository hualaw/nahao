<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['page_admin']['uri_segment'] = 3;
$config['page_admin']['num_links'] = 3;
$config['page_admin']['full_tag_open'] = '<ul class="pagination">';
$config['page_admin']['full_tag_close'] = '</ul>';
$config['page_admin']['first_link'] = '首页';
$config['page_admin']['first_tag_open'] = '<li>';
$config['page_admin']['first_tag_close'] = '</li>';
$config['page_admin']['last_link'] = '尾页';
$config['page_admin']['last_tag_open'] = '<li>';
$config['page_admin']['last_tag_close'] = '</li>';
$config['page_admin']['cur_tag_open'] = '<li class="active"><a href="#">';
$config['page_admin']['cur_tag_close'] = '</a></li>';
$config['page_admin']['prev_link'] = '«';
$config['page_admin']['next_link'] = '»';
$config['page_admin']['next_tag_open'] = '<li>';
$config['page_admin']['next_tag_close'] = '</li>';
$config['page_admin']['prev_tag_open'] = '<li>';
$config['page_admin']['prev_tag_close'] = '</li>';
$config['page_admin']['num_tag_open'] = '<li>';
$config['page_admin']['num_tag_close'] = '</li>';



/* $config['page_student']['uri_segment'] = 4;
$config['page_student']['num_links'] = 3;
$config['page_student']['full_tag_open'] = '<ul class="pagination">';
$config['page_student']['full_tag_close'] = '</ul>';
$config['page_student']['first_link'] = '首页';
$config['page_student']['first_tag_open'] = '<li>';
$config['page_student']['first_tag_close'] = '</li>';
$config['page_student']['last_link'] = '尾页';
$config['page_student']['last_tag_open'] = '<li>';
$config['page_student']['last_tag_close'] = '</li>';
$config['page_student']['cur_tag_open'] = '<li class="active"><a href="#">';
$config['page_student']['cur_tag_close'] = '</a></li>';
$config['page_student']['prev_link'] = '«';
$config['page_student']['next_link'] = '»';
$config['page_student']['next_tag_open'] = '<li>';
$config['page_student']['next_tag_close'] = '<li>';
$config['page_student']['prev_tag_open'] = '<li>';
$config['page_student']['prev_tag_close'] = '<li>';
$config['page_student']['num_tag_open'] = '<li>';
$config['page_student']['num_tag_close'] = '</li>'; */


$config['page_student']['uri_segment']      =   4;
$config['page_student']['num_links']        =   3;
$config['page_student']['full_tag_open']    =   '<div class="page cf">';
$config['page_student']['full_tag_close']   =   '</div>';
$config['page_student']['first_link']       =   '首页';;
$config['page_student']['last_link']        =   '尾页';
$config['page_student']['cur_tag_open']     =   '<a class="active" >';
$config['page_student']['cur_tag_close']    =   '</a>';
$config['page_student']['prev_link']        =   '上一页';
$config['page_student']['next_link']        =   '下一页';
$config['page_student']['next_tag_open'] = '<div class="next fl">';
$config['page_student']['next_tag_close'] = '</div>';
$config['page_student']['prev_tag_open'] = '<div class="prev fl">';
$config['page_student']['prev_tag_close'] = '</div>';

$config['page_teacher']['uri_segment']      =   3;
$config['page_teacher']['num_links']        =   3;
$config['page_teacher']['full_tag_open']    =   '<div class="page cf">';
$config['page_teacher']['full_tag_close']   =   '</div>';
$config['page_teacher']['first_link']       =   '首页';
$config['page_teacher']['last_link']        =   '尾页';
$config['page_teacher']['cur_tag_open']     =   '<a class="active" >';
$config['page_teacher']['cur_tag_close']    =   '</a>';
$config['page_teacher']['prev_link']        =   '上一页';
$config['page_teacher']['next_link']        =   '下一页';
$config['page_teacher']['next_tag_open'] = '<div class="next fl">';
$config['page_teacher']['next_tag_close'] = '</div>';
$config['page_teacher']['prev_tag_open'] = '<div class="prev fl">';
$config['page_teacher']['prev_tag_close'] = '</div>';

$config['page_user']['uri_segment']      =   4;
$config['page_user']['num_links']        =   3;
$config['page_user']['full_tag_open']    =   '<div class="page cf"><ul>';
$config['page_user']['full_tag_close']   =   '</ul></div>';
$config['page_user']['first_link']       =   '首页';
$config['page_user']['last_link']        =   '尾页';
$config['page_user']['cur_tag_open']     =   '<li class="active"><a href="javascript:void(0);">';
$config['page_user']['cur_tag_close']    =   '</a></li>';
$config['page_user']['prev_link']        =   '上一页';
$config['page_user']['next_link']        =   '下一页';
$config['page_user']['next_tag_open'] = '<li>';
$config['page_user']['next_tag_close'] = '</li>';
$config['page_user']['prev_tag_open'] = '<li>';
$config['page_user']['prev_tag_close'] = '</li>';
$config['page_user']['num_tag_open'] = '<li>';
$config['page_user']['num_tag_close'] = '</li>';
$config['page_user']['page_query_string'] = TRUE;