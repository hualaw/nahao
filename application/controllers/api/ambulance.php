<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ambulance extends NH_Controller {

    function __construct(){
        parent::__construct();
        header("Content-type: text/html; charset=utf-8");
        if(ENVIRONMENT=='production'){
            exit;
        }
    }
    /**
     * 重新生成教室ID 并且绑定pdf等
     * @author yanrui@tizi.com
     */
    public function change_classroom(){
        $str_type = $this->uri->rsegment(3) ? urldecode(trim($this->uri->rsegment(3))) : 'class';
        $str_ids = $this->uri->rsegment(4) ? urldecode(trim($this->uri->rsegment(4))) : '';
        if(in_array($str_type,array('round','class')) || $str_ids){
            $this->load->model('business/admin/business_class','class');
            $mix_class_ids = '';
            if($str_type=='round'){
                $mix_round_ids = explode('|',$str_ids);
                $arr_class_ids = $this->class->get_class_ids_by_round_id($mix_round_ids);
                foreach($arr_class_ids as $k => $v){
                    $mix_class_ids[] = $v['id'];
                }
            }else{
                $mix_class_ids = explode('|',$str_ids);
            }
            if($mix_class_ids){
                $arr_classes = $this->class->get_class_by_id($mix_class_ids);
//                o($arr_classes,true);
                foreach($arr_classes as $k => $v){
                    if($v['parent_id'] > 0 AND $v['classroom_id'] > 0){
                        var_dump($v);
                        $arr_classroom_param = array(
                            'name' => $v['title'],
                            'start_at' => date('Y-m-d H:i:s',$v['begin_time']),
                            'end_at' => date('Y-m-d H:i:s',$v['end_time'])
                        );
                        $int_classroom_id = general_classroom_id($arr_classroom_param);
                        if($int_classroom_id > 0){
                            if($v['courseware_id'] > 0){
                                $bool_set = set_courseware_to_classroom($int_classroom_id,$v['courseware_id']);
                            }
                            $arr_param = array(
                                'classroom_id' => $int_classroom_id,
                            );
                            $arr_where = array(
                                'id' => $v['id']
                            );
                            $bool_return = $this->class->update_class($arr_param, $arr_where);
                            echo 'ID为'.$v['id'].'的课堂，教室由'.$v['classroom_id'].'调整为'.$int_classroom_id;
                        }
                    }
                }
            }
        }
    }

    public function get(){
//        test_nahao_classroom('api/meetings/503/files/');
    }

    public function get_phone(){
        $str_phone = '';
        $int_user_id = $this->uri->rsegment(3) ? intval($this->uri->rsegment(3)) : 0;
        if($int_user_id){
            $str_phone = get_pnum_phone_server($int_user_id);
        }
        echo json_encode(array('phone' =>$str_phone));
    }

    public function get_pdf(){
        set_time_limit(0);
        $arr_courseware = $this->db->query("select * from courseware")->result_array();
//        var_dump($arr_courseware);exit;

        $obj_curl = curl_init();
//    curl_setopt($obj_curl, CURLOPT_HEADER,array("Content-length: 99999") ); // 设置header 过滤HTTP头
        curl_setopt($obj_curl, CURLOPT_HEADER,0); // 设置header 过滤HTTP头
        curl_setopt($obj_curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($obj_curl, CURLOPT_TIMEOUT, 10);

        foreach($arr_courseware as $k => $v){
            $str_url = 'http://nahao-pdf.qiniudn.com/';
            $str_url = $str_url.$v['id'].'/'.$v['name'];
            curl_setopt($obj_curl,CURLOPT_URL,$str_url);
            $str_response = curl_exec($obj_curl);
            $arr_return = curl_getinfo($obj_curl);
            if($arr_return['http_code']==404){
                echo $str_url."\n\r";
            }
//            var_dump(curl_getinfo($obj_curl));exit;
        }
        curl_close($obj_curl);

    }
}