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

    public function replace(){
        error_reporting(E_ALL);
        ini_set('display_errors', true);
        set_time_limit(0);
        $arr = $this->db->select('id,description as d')->from(TABLE_ROUND)->get()->result_array();
        foreach($arr as $k => $v){
            preg_match_all('/<img(.*)src="http:\/\/(.*).com\/(.*)\?imageView\/(.*)\/w\/600"/',$v['d'],$arr_match);
            if($arr_match[0]){
                var_dump($arr_match);
                foreach($arr_match[0] as $kk =>$vv){
                    $new = '<img '.$arr_match[1][$kk].' src="'.str_replace('1',nahao_hash($arr_match[3][$kk],4),NH_QINIU_URL).$arr_match[3][$kk].'/c.720.jpg"';
                    $v['d'] = str_replace($arr_match[0][$kk],$new,$v['d']);
                }
                echo $v['id'].'--';
                var_dump($v['d']);
                $data = array('description'=> $v['d']);
                $this->db->where('id',$v['id']);
                $this->db->update(TABLE_COURSE,$data);
            }
        }
    }

    public function set_next_class_time(){
        $arr_where = array(
            'sale_status >' => ROUND_SALE_STATUS_NO_PASS,
            'sale_status <' => ROUND_SALE_STATUS_OFF,
            'is_test' => 0,
            'is_live' => 0
        );
        $arr_round = $this->db->select('id,title,sale_status,teach_status')->from(TABLE_ROUND)->where($arr_where)->get()->result_array();
//        $arr_round = array_slice($arr_round,0,5);
//        o($arr_round,true);
        $arr_new_class = array();

        $this->load->model('business/admin/business_class','class');
        foreach($arr_round as $k => $v){
            echo '=='.$v['id'].'==';
            if(in_array($v['teach_status'],array(ROUND_TEACH_STATUS_INIT,ROUND_TEACH_STATUS_TEACH))){
                $arr_where_class = array(
                    'status' => CLASS_STATUS_SOON_CLASS,
                    'round_id' => $v['id'],
                );
                $arr_classes = $this->db->select('*')->from(TABLE_CLASS)->where($arr_where_class)->get()->result_array();
                if($arr_classes){
                    $int_next_time = $arr_classes[0]['begin_time'];
                    $int_round_id = $arr_classes[0]['round_id'];
                    echo $int_round_id.'--';
                    o(date('Y-m-d H:i:s',$int_next_time));
                    //update
                    $data = array('next_class_begin_time'=> $int_next_time);
                    $this->db->where('id',$int_round_id);
                    $this->db->update(TABLE_ROUND,$data);
                    o($this->db->last_query());
                }else{
                    echo "<br >";
                }
            }elseif(in_array($v['teach_status'],array(ROUND_TEACH_STATUS_FINISH,ROUND_TEACH_STATUS_OVER))){
                $arr_classes = $this->class->get_classes_by_round_id($v['id']);
//            o($arr_classes);
                foreach($arr_classes as $kk => $vv){
                    if($vv['parent_id'] > 0){
                        $arr_new_class[] = $vv;
                    }
                }
//                o($arr_new_class);
                $arr_last_class = $arr_new_class[(count($arr_new_class)-1)];
                $int_next_time = $arr_last_class['begin_time'];
                $int_round_id = $arr_last_class['round_id'];
                echo $int_round_id.'--';
                o(date('Y-m-d H:i:s',$int_next_time));
                $data = array('next_class_begin_time'=> $int_next_time);
                $this->db->where('id',$int_round_id);
                $this->db->update(TABLE_ROUND,$data);
                o($this->db->last_query());
            }
        }
//        o($arr_new_class);
    }


}