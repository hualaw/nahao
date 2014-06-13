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
        if($int_round_id > 0){
            $this->load->model('business/admin/business_round','round');
            $arr_round = $this->round->get_round_by_id($int_round_id);
            $arr_classes = $this->class->get_classes_by_round_id($int_round_id);
            $int_chapter_count = $int_section_count = 0 ;
            foreach($arr_classes as $class){
                if($class['parent_id'] == 0){
                    ++$int_chapter_count;
                }else{
                    ++$int_section_count;
                }
            }
//            o($arr_classes,true);
        }
        $this->smarty->assign('round',$arr_round);
        $this->smarty->assign('classes',$arr_classes);
        $this->smarty->assign('class_status',config_item('class_teach_status'));
        $this->smarty->assign('chapter_count',$int_chapter_count);
        $this->smarty->assign('section_count',$int_section_count);
        $this->smarty->assign('view', 'class_list');
        $this->smarty->display('admin/layout.html');
    }

//self::json_output($this->arr_response);

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
//        o($int_class_id);
//        o($int_courseware_id,true);
        if($int_class_id > 0 AND $int_courseware_id > 0){
            $bool_return = $this->class->add_courseware($int_class_id,$int_courseware_id);
            if($bool_return==true){
                $this->arr_response['status'] = 'ok';
                $this->arr_response['msg'] = '添加成功';
                $this->arr_response['redirect'] = '/class/index/'.$int_class_id;
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * enter classroom
     * @author yanrui@tizi.com
     */
    public function enter(){
        $str_classroom_url = '';
        $int_classroom_id = $this->uri->rsegment(3) ? $this->uri->rsegment(3) : 0;
        if($int_classroom_id){
            $str_classroom_url = enter_classroom($int_classroom_id,NH_MEETING_TYPE_SUPER_ADMIN);
        }

//        echo $str_classroom_url;exit;
//        $ch = curl_init($str_classroom_url);
//        //var_dump($ch);
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HEADER, false);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
//        $output = curl_exec($ch);
//
//        //echo "error:".curl_error($ch)."<br>";
//        //echo "errno:".curl_errno($ch)."<br>";
//        curl_close($ch);
//        echo $output;exit;
o($str_classroom_url,true);
//        echo nh_curl($str_classroom_url,false);exit;
        $str_iframe = '<iframe src="'.$str_classroom_url.'" width="100%" height="100%" frameborder="0" name="_blank" id="_blank" ></iframe>';

        $this->smarty->assign('iframe',$str_iframe);
        $this->smarty->assign('view', 'classroom');
        $this->smarty->display('admin/layout.html');
    }
}