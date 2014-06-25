<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Courseware相关逻辑
 * Class Business_Courseware
 * @author yanrui@tizi.com
 */
class Business_Courseware extends NH_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_courseware');
    }

    /**
     * 添加courseware
     * @param $arr_param
     * @author yanrui@tizi.com
     */
    public function create_courseware($arr_param){
        if(is_array($arr_param) AND $arr_param){
            $this->model_courseware->create_courseware($arr_param);
        }
    }

    /**
     * 根据ID获取courseware
     * @param $arr_courseware_id
     * @return array
     */
    public function get_courseware_by_id($arr_courseware_id){
        $arr_return = array();
        if(is_array($arr_courseware_id) AND $arr_courseware_id){
            $str_table_range = 'courseware';
            $str_result_type = 'list';
            $str_fields = '*';
            $arr_where = array(
                'id' => $arr_courseware_id
            );
            $arr_return = $this->model_courseware->get_courseware_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
            if($arr_return){
                foreach($arr_return as $k => $v){
                    $arr_return[$k]['download_url'] = NH_PDF_DOWNLOAD_URL.$v['id'].'/'.$v['name'];
                }
            }
        }
        return $arr_return;
    }
}