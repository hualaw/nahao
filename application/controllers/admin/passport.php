<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Passport extends NH_Admin_Controller {

    /**
     * @author yanrui@tizi.com
     */
    public function index()
    {
        $str_cookie = get_cookie('_'.ROLE_ADMIN);
        if(json_decode($str_cookie)){
            redirect('/welcome/main');
        }
        $this->load->view('admin/signin');
    }

    public function login(){
        $str_username = $this->input->post('username') ? trim($this->input->post('username')) : '';
        $str_password = $this->input->post('password') ? trim($this->input->post('password')) : '';

        $str_redirect = '/';
//        $arr_user_info = $this->admin->login($str_username,$str_password);

        $arr_user_info = $this->admin->get_admin_by_username($str_username);
        $str_salt = $arr_user_info['salt'];
        if(sha1($str_salt.sha1($str_password))===$arr_user_info['password']){
            $str_redirect = '/welcome/main';
            $arr_cookie = array(
                'user_id' => $arr_user_info['id'],
                'user_name' => $arr_user_info['user_name'],
                'login_time' => time(),
            );
            $str_cookie_name = '_'.ROLE_ADMIN;
            set_cookie($str_cookie_name,json_encode($arr_cookie),0);
        }
        redirect($str_redirect);

        //超级管理员id为1 无法冻结 其他用户冻结后无法登陆
//        if ($data['status'] == 1 || $data['id'] == 1) {
//            $token = authcode("{$data['id']}\t{$data['password']}", 'ENCODE');
//            $token_name = 'token-' . USER_TYPE_ADMIN;
//            set_cookie('last_time', time(), 0);
//            set_cookie($token_name, $token, 0);
//            redirect('/');
//        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */