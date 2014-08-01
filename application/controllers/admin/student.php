<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Student管理
 * Class Student
 * @author yanrui@tizi.com
 */
class Student extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
        $this->load->model('business/admin/business_student','student');
    }

    /**
     * Student index
     * @author yanrui@tizi.com
     */
    public function index(){
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
//        $int_start = $this->input->get('page') ? intval($this->input->get('page')) : 0 ;
        $int_stage = $this->input->get('stage') ? intval($this->input->get('stage')) : 0 ;
        $int_grade = $this->input->get('grade') ? intval($this->input->get('grade')) : 0 ;
        $int_province = $this->input->get('province') ? intval($this->input->get('province')) : 0 ;
        $int_course_type = $this->input->get('course_type') ? intval($this->input->get('course_type')) : 0 ;
        $int_subject = $this->input->get('subject') ? intval($this->input->get('subject')) : 0 ;
        $int_gender = $this->input->get('gender') ? intval($this->input->get('gender')) : 0 ;
        $int_has_bought = $this->input->get('has_bought') ? intval($this->input->get('has_bought')) : 0 ;
        $int_register_type = $this->input->get('register_type') ? intval($this->input->get('register_type')) : 0 ;
        $int_search_type = $this->input->get('search_type') ? intval($this->input->get('search_type')) : 0 ;
        $str_search_value = $this->input->get('search_value') ? trim($this->input->get('search_value')) : '' ;

        $arr_where = array();
        if($int_stage > 0){
            $arr_where['stage'] = $int_stage;
        }
        if($int_grade > 0){
            $arr_where['grade'] = $int_grade;
        }
        if($int_province > 0){
            $arr_where['province'] = $int_province;
        }
        if($int_course_type > 0){
            $arr_where['course_type'] = $int_course_type;
        }
        if($int_subject > 0){
            $arr_where['subject'] = $int_subject;
        }
        if($int_gender > 0){
            $arr_where['gender'] = $int_gender;
        }
        if($int_has_bought > 0){
            $arr_where['has_bought'] = --$int_has_bought;
        }
        if($int_register_type > 0){
            $arr_where['register_type'] = $int_register_type;
        }
        if($int_search_type > 0 AND $str_search_value != ''){
            if($int_search_type == 1){//昵称
                $arr_where['nickname'] = $str_search_value;
            }elseif($int_search_type == 2){//邮箱
                $arr_where['email'] = $str_search_value;
            }elseif($int_search_type == 3){//手机号
//                $int_user_id = 0;//get from phone server
                $int_user_id = get_uid_phone_server($str_search_value);
                $arr_where['id'] = $int_user_id;
            }elseif($int_search_type == 4){//用户ID
                $int_user_id = intval($str_search_value);
                if($int_user_id > 0){
                    $arr_where['id'] = $int_user_id;
                }
            }elseif($int_search_type == 5){//真名
                $arr_where['realname'] = $str_search_value;
            }
        }

//        o($arr_where);

        $int_count = $this->student->get_student_count($arr_where);
        $arr_list = $this->student->get_student_list($arr_where, $int_start,PER_PAGE_NO);
//        o($int_count,true);
        $arr_tmp_areas = $arr_final_areas = array();
        foreach($arr_list as $k => $v){
            if($v['province']>0){
                $arr_tmp_areas[] = $v['province'];
                $arr_tmp_areas[] = $v['city'];
                $arr_tmp_areas[] = $v['area'];
                $arr_final_areas[$k] = array('province' => $v['province'],'city' => $v['city'],'area'=> $v['area']);
            }
        }
        $arr_tmp_areas = array_unique($arr_tmp_areas);
        $this->load->model('business/common/business_area','area');
        $arr_areas = $this->area->get_areas_by_ids_like_kv($arr_tmp_areas);
//        o($arr_final_areas);
        foreach($arr_final_areas as $k => $v){
//            $arr_list[$k]['final_area'] = ($v['province'] > 0 ? $arr_areas[$v['province']] : '').$arr_areas[$v['city']].$arr_areas[$v['area']];
            $arr_list[$k]['final_area'] = (($v['province'] > 0 AND isset($arr_areas[$v['province']]))? $arr_areas[$v['province']] : '').(($v['city'] > 0 AND isset($arr_areas[$v['city']])) ? $arr_areas[$v['city']] : '').($v['area'] > 0 ? $arr_areas[$v['area']] : '');
//            o($arr_list[$k]['final_area']);
        }
        $arr_provinces = $this->area->get_provinces();
        $this->load->model('business/common/business_subject','subject');
        $arr_subjects = $this->subject->get_subjects();
        $this->load->model('business/common/business_course_type','course_type');
        $arr_course_types = $this->course_type->get_course_types();

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['per_page'] = PER_PAGE_NO;
        $config['total_rows'] = $int_count;
        $this->pagination->initialize($config);

        $this->smarty->assign('page',$this->pagination->create_links());
        $this->smarty->assign('count',$int_count);
        $this->smarty->assign('list',$arr_list);
        $this->smarty->assign('stages',config_item('stage'));
        $this->smarty->assign('grades',config_item('grade'));
        $this->smarty->assign('provinces',$arr_provinces);
        $this->smarty->assign('course_types',$arr_course_types);
        $this->smarty->assign('subjects',$arr_subjects);
        $this->smarty->assign('search_type',config_item('admin_student_list_search_type'));
        $this->smarty->assign('register_type',config_item('admin_round_list_register_type'));
        $this->smarty->assign('genders',config_item('gender'));
        $this->smarty->assign('has_bought',config_item('has_bought'));
        $this->smarty->assign('query_param', $arr_query_param);
        $this->smarty->assign('view', 'student_list');
        $this->smarty->display('admin/layout.html');
    }

    /**
     * student账户禁用启用
     * @author yanrui@tizi.com
     */
    public function active(){
        if($this->is_ajax() AND $this->is_post()){
            $int_user_id = intval($this->input->post('user_id'));
            $int_status = intval($this->input->post('status'));
            if($int_user_id > 1 AND in_array($int_status,array(0,1))){
                $arr_param = array(
                    'status' => $int_status
                );
                $arr_where = array(
                    'id' => $int_user_id
                );
                $arr_info_where = array(
                    'user_id' => $int_user_id
                );
                $bool_return = $this->student->update_student($arr_param,$arr_where,$arr_info_where);
                if($bool_return > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '修改成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }
}