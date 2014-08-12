<?php

class oauth extends NH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('business/common/business_oauth');
        $this->load->library('TiziOauth');
        $this->load->config('oauth');
    }

    public function tizi_oauth_login(){
        $config = $this->config->item('tizi');
        $tizi = new TiziOauth($config);
        $tizi->tizi_login();
    }

    public function tizi_oauth_callback(){
        $config = $this->config->item('tizi');
        $tizi = new TiziOauth($config);
        $result = $tizi->get_accesstoken();

        if (!isset($result['error'])) {
            $openid = $tizi->get_openid();
            if($openid){
                $user_info = $tizi->get_user_info();
                if(!empty($user_info)){
                    $user = $this->business_oauth->get_user_by_openid($openid);
                    if($user){
                        $this->business_oauth->do_login($user);
                    }else{
                        $user['openid'] = $user_info['openId'];
                        $user['nickname'] = $user_info['nick'];
                        $user_id = $this->business_oauth->create_user($user);
                        if($user_id){
                            $user['id'] = $user_id;                           
                            $this->business_oauth->do_login($user);
                        }
                    }
                }
            }
        }else{
            //var_dump($result['error']);
        }
        redirect('/');
    }
}
