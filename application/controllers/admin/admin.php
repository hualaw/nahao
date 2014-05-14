<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends NH_admin_Controller {

    public function create_admin(){
        $str_nickname = trim($this->input->post('nickname'));
        $arr_param['nickname'] = $str_nickname;
        $this->load->model('business/admin/business_admin','admin');
        $int_last_id = $this->admin->create_admin($arr_param);
    }
}