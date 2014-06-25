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
}