<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 课堂管理
 * Class Classes
 * @author yanrui@tizi.com
 */
class Classes extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
        'redirect' => '/round'
    );

    /**
     * classes list
     * @author yanrui@tizi.com
     */
    public function index () {
        $int_round_id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $arr_class = array();
        $int_first_class_id = $int_last_class_id = 0;
        $arr_round = $arr_classes =$int_chapter_count = $int_section_count = '';
        if($int_round_id > 0){
            $this->load->model('business/admin/business_round','round');
            $arr_round = $this->round->get_round_by_id($int_round_id);
            $arr_classes = $this->class->get_classes_by_round_id($int_round_id);
            $int_chapter_count = $int_section_count = 0 ;
            foreach($arr_classes as $k => $class){
                if($class['parent_id'] == 0){
                    ++$int_chapter_count;
                }else{
                    ++$int_section_count;
                    $int_first_class_id = $int_first_class_id > 0 ? $int_first_class_id : $class['id'];
                    $int_last_class_id = $class['id'];
                }
            }
//            o($arr_round,true);
        }
        $this->smarty->assign('round',$arr_round);
        $this->smarty->assign('first_class_id',$int_first_class_id);
        $this->smarty->assign('last_class_id',$int_last_class_id);
        $this->smarty->assign('classes',$arr_classes);
        $this->smarty->assign('class_status',config_item('class_teach_status'));
        $this->smarty->assign('chapter_count',$int_chapter_count);
        $this->smarty->assign('section_count',$int_section_count);
        $this->smarty->assign('view', 'class_list');
        $this->smarty->display('admin/layout.html');
    }

    /**
     * class update
     * @author yanrui@tizi.com
     */
    public function update(){
        $int_round_id = $this->input->post('round_id') ? intval($this->input->post('round_id')) : 0;
        $int_class_id = $this->input->post('class_id') ? intval($this->input->post('class_id')) : 0;
        $int_is_first = $this->input->post('is_first') ? intval($this->input->post('is_first')) : 0;
        $int_is_last = $this->input->post('is_last') ? intval($this->input->post('is_last')) : 0;
        $str_title = $this->input->post('title') ? trim($this->input->post('title')) : '';
        $str_begin_time = $this->input->post('begin_time') ? trim($this->input->post('begin_time')) : '';
        $str_end_time = $this->input->post('end_time') ? trim($this->input->post('end_time')) : '';

        $int_begin_time = strtotime($str_begin_time);
        $int_end_time = strtotime($str_end_time);

        $arr_response = array(
            'status' => 'error',
            'msg' => '时间不可用'
        );

        if($int_round_id > 0 AND $int_class_id > 0 AND $str_title AND $str_begin_time > 0 AND $str_end_time > 0){
            $bool_flag = true;
            $this->load->model('business/admin/business_round','round');
            $arr_round = $this->round->get_round_by_id($int_round_id);
//            o($arr_round,true);
            if($arr_round /*AND isset($arr_round['start_time'])*/){
                $str_config_name = ($arr_round['is_test']==0 AND in_array(ENVIRONMENT,array('production'))) ?  'production_round_time_config' : 'testing_round_time_config' ;
                $arr_time_config = config_item($str_config_name);
                if($int_end_time < $int_begin_time + $arr_time_config['class_min_long']){
                    $bool_flag = false;
                    $arr_response['msg'] = '课长太短';
                }elseif($int_end_time > $int_begin_time + $arr_time_config['class_max_long']){
                    $bool_flag = false;
                    $arr_response['msg'] = '课长太长';
                }
//                if(($arr_round['start_time']+$arr_time_config['before_first_class']) > $int_begin_time){
//                    $bool_flag = false;
//                    $arr_response['msg'] = '课开始时间要晚于轮开始时间后一定时间';
//                }

                if($bool_flag == true){
                    $arr_classes = $this->class->get_classes_by_round_id($int_round_id);
                    foreach($arr_classes as $k => $v){
                        if($v['parent_id'] > 0 AND $v['id'] != $int_class_id){
                            if(($v['begin_time']-$arr_time_config['class_between_long'] < $int_begin_time AND $int_begin_time < $v['end_time']+$arr_time_config['class_between_long']) OR ($v['end_time']-$arr_time_config['class_between_long'] < $int_begin_time AND $int_end_time < $v['end_time']+$arr_time_config['class_between_long'])){
                                $bool_flag = false;
                                $arr_response['msg'] = '时间已占用';
                                break;
                            }
                        }
                    }
                    if($bool_flag == true){
                        $arr_class = $this->class->get_class_by_id($int_class_id);
                        $int_classroom_id = $arr_class['classroom_id'];
                        $int_courseware_id = $arr_class['courseware_id'];

//                        o($arr_class,true);
                        if($arr_class['classroom_id']==0){
                            $arr_classroom_param = array(
                                'name' => $str_title,
                                'start_at' => $str_begin_time,
                                'end_at' => $str_end_time
                            );
                            //o($arr_classroom_param,true);
                            $int_classroom_id = general_classroom_id($arr_classroom_param);
                        }
                        $bool_add_courseware = set_courseware_to_classroom($int_classroom_id,$int_courseware_id);
//            o($bool_add_courseware,true);

                        $arr_param = array(
                            'title' => $str_title,
                            'classroom_id' => $int_classroom_id,
                            'begin_time' => strtotime($str_begin_time),
                            'end_time' => strtotime($str_end_time)
                        );
                        $arr_where = array(
                            'id' => $int_class_id
                        );
                        $this->class->update_class($arr_param,$arr_where);
                        if($int_is_first==1 OR $int_is_last==1){
                            $arr_param_round = $int_is_first == 1 ? array(
                                'start_time' => strtotime($str_begin_time)-3600,
                                'next_class_begin_time' => $int_begin_time
                            ) : array(
                                'end_time' => strtotime($str_end_time)+3600
                            );

                            $arr_where_round = array(
                                'id' => $int_round_id
                            );
                            $arr_round = $this->round->update_round($arr_param_round,$arr_where_round);
                        }
                        $arr_response = array(
                            'status' => 'ok',
                            'msg' => '修改成功'
                        );
                    }
                }
            }
        }
        self::json_output($arr_response);
    }

    public function token(){
//        $str_signature = get_meeting_signature();
//        $str = '?nonce='.TIME_STAMP.'&signature='.$str_signature.'&app_key='.NH_MEETING_ACCESS_KEY;
//        o($str,true);
        $arr_token = array(
            'token' => get_meeting_token(0,NH_MEETING_TYPE_SUPER_ADMIN)
        );
        self::json_output($arr_token);
    }

    /**
     * add / update courseware to class
     * @author yanrui@tizi.com
     */
    public function add_courseware(){
        $int_class_id = $this->input->post('class_id') ? intval($this->input->post('class_id')) : 0;
        $int_courseware_id = $this->input->post('courseware_id') ? intval($this->input->post('courseware_id')) : 0;
        $int_create_time = $this->input->post('create_time') ? strtotime($this->input->post('create_time')) : 0;
        $str_filename = $this->input->post('filename') ? trim($this->input->post('filename')) : '';
        $int_filesize = $this->input->post('filesize') ? intval($this->input->post('filesize')) : 0;
        $int_filetype = $this->input->post('filetype') ? intval($this->input->post('filetype')) : 0;

        if($int_class_id > 0 AND $int_courseware_id > 0 AND $int_create_time > 0 AND $str_filename){
            $arr_courseware = array(
                'id' => $int_courseware_id,
                'create_time' => $int_create_time,
                'name' => $str_filename,
                'filesize' => $int_filesize,
                'filetype' => $int_filetype,
            );
            $bool_return = $this->class->add_courseware($int_class_id,$arr_courseware);
            if($bool_return==true){
                $this->arr_response['status'] = 'ok';
                $this->arr_response['msg'] = '添加成功';
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * 管理员进教室
     * @author yanrui@tizi.com
     */
    public function enter(){
        $int_classroom_id = $this->uri->rsegment(3) ? $this->uri->rsegment(3) : 0;
        $arr_class = $this->class->get_class_by_classroom_id($int_classroom_id);
        if($arr_class){
        	$arr_class_map = config_item('round_class_map');
        	$int_classroom_id = isset($arr_class_map[$int_classroom_id]) ? $arr_class_map[$int_classroom_id] : $int_classroom_id ;
            $str_iframe = self::enter_classroom($int_classroom_id,NH_MEETING_TYPE_ADMIN,array('class_title'=>$arr_class['title']));
            $this->smarty->assign('js_module', 'classRoom');
            $this->smarty->assign('classroom_id', $int_classroom_id);
            $this->smarty->assign('iframe', $str_iframe);
            $this->smarty->display('admin/classroom.html');
        }else{
            die('');
        }
    }

    /**
     * 预览pdf
     * @author yanrui@tizi.com
     */
    public function preview(){
        $int_courseware_id = $this->uri->rsegment(3) ? $this->uri->rsegment(3) : 0;
        if($int_courseware_id){
            $arr_courseware = get_courseware_info($int_courseware_id);
            $this->smarty->assign('coruseware_id', $arr_courseware['id']);
            $this->smarty->assign('pagenum', $arr_courseware['pagenum']);
            $this->smarty->assign('swfpath', $arr_courseware['swfpath']);
            $this->smarty->assign('view', 'preview');
            $this->smarty->display('admin/layout.html');
        }
    }

    /**
     * reload courseware
     * @author yanrui@tizi.com
     */
    public function reload(){
        $int_classroom_id = $this->input->post('classroom_id') ? intval($this->input->post('classroom_id')) : 0;
        $arr_response = array(
            'status' => 'error',
            'msg' => '刷新失败'
        );
        if($int_classroom_id > 0){
//            test_nahao_classroom('api/meetings/233/files/');
            $arr_old_ids = get_coursewares_by_classroom_id($int_classroom_id);
//            o($arr_old_ids,true);
            if($arr_old_ids){
                foreach($arr_old_ids as $id){
                    delete_courseware_by_classroom_id($int_classroom_id,$id);
                }
            }
            $arr_class = $this->class->get_class_by_classroom_id($int_classroom_id);
            if($arr_class AND $arr_class['courseware_id']){
                set_courseware_to_classroom($int_classroom_id,$arr_class['courseware_id']);
            }
            reload_courseware($int_classroom_id);
            $arr_response = array(
                'status' => 'ok',
                'msg' => '刷新成功'
            );
        }
        self::json_output($arr_response);
    }

    public function delete(){
        $int_class_id = $this->input->post('class_id') ? $this->input->post('class_id') : 0;
        $arr_response = array(
            'status' => 'error',
            'msg' => '删除失败',
        );
        if($int_class_id > 0){
//            $arr_where = array(
//                'id' => $int_class_id
//            );
//            o($arr_where,true);
            $return = $this->class->delete_classes($int_class_id);
            if($return){
                $arr_response = array(
                    'status' => 'ok',
                    'msg' => '删除成功',
                );
            }
        }
        self::json_output($arr_response);
    }

    public function download(){
        header('content-type: text/html; charset=utf-8');
        #判断是否登录
        if(!$this->is_login)
        {
            redirect('/login');
        }
        $int_courseware_id = intval($this->uri->rsegment(3));
        $this->load->model('business/common/business_courseware','courseware');
        $array_courseware = $this->courseware->get_courseware_by_id(array($int_courseware_id));
        if (empty($array_courseware))
        {
            show_error('抱歉!这节课没有上传课件');
        }
        //echo $_SERVER["HTTP_USER_AGENT"];
        $wordStr = $array_courseware['0']['download_url'];
        $file_name = $array_courseware['0']['name'];
        $file_name = urlencode($file_name);
        $file_name = str_replace("+", "%20", $file_name);// 替换空格
        download($wordStr,$file_name);
    }
}