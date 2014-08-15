<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");

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
        //登录之后，要跳转到首页
        if($this->is_login) redirect('/');
        
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
                //var_dump($user_info);
                //die();
                if(!empty($user_info)){
                    $user = $this->business_oauth->get_user_by_openid($openid);
                    if($user){
                        $this->business_oauth->do_login($user);
                    }else{
                        $user['openid'] = $user_info['openId'];
                        $user['nickname'] = $user_info['nick'];
                        $user['email'] = $user_info['email'];
                        $user['phone_mask'] = $user_info['phone'];
                        $userinfo['realname'] = $user_info['nick'];
                        //create user
                        $user_id = $this->business_oauth->create_user($user);
                        if($user_id){
                            $userinfo['user_id'] = $user_id;
                            //create user_info
                            $this->business_oauth->create_user_info($userinfo);
                            //update avatar
                            $result = $this->business_oauth->update_avatar($user_id,$user_info['avatar']);
                            //do login
                            $user['id'] = $user_id;   
                            $user['avatar'] = $result['avatar_key'];                       
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
