<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Oauth相关逻辑
 * Class Business_Oauth
 * @author changlinjie@tizi.com
 */
class Business_Oauth extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_user');
    }

    /**
     * 根据从第三方获取的openid获取user
     * @param string $openid
     * @return array
     * @author changlinjie@tizi.com
     */
    public function get_user_by_openid($openid)
    {
        $arr_return = array();
        if($openid){
            $str_table_range = 'user';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'openid' => $openid,
                'status' => 1,
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    public function create_user($user){
        $user_table_data = array(
            'register_time' => time(),
            'register_ip' => ip2long($this->input->ip_address()),
            'source' => 0,
            'phone_verified' => 0,
            'email_verified' => 0,
            'reg_type' => 3
            );
        $user_table_data = array_merge($user_table_data,$user);
        $user_id = $this->model_user->create_user($user_table_data);
        //if insert table failed, the $uer_id is int zero
        if($user_id === 0)
        {
            $user_table_data['error'] = 'user_insert_failed';
            return $this->_log_reg_info(ERROR, 'reg_db_error', $user_table_data);
        }
        return $user_id;
    }

    public function create_user_info($userinfo){
        //insert a record into user_info table
        $insert_affected_rows = $this->model_user->create_user_info($userinfo);
        if($insert_affected_rows < 1)
        {
            //标记user表里的记录为无效状态
            $this->model_user->update_user(array('status'=>0), array('id'=>$user_id));
            $user_table_data['error'] = 'user_info_insert_failed';
            return $this->_log_reg_info(ERROR, 'reg_db_error', $user_table_data);
        }
        return true;    
    }

    public function update_avatar($user_id,$avatar_oauth){
        $content = file_get_contents($avatar_oauth);
        if(empty($content)){
            return '';
        }
        $upload_dir = dirname(APPPATH) . "/uploads/";
        $tmpfname = tempnam($upload_dir, "avatar");
        $handle = fopen($tmpfname, "w");
        fwrite($handle, $content);
        fclose($handle);
        //generate param for uploading to qiniu
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys ( NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY );
        $obj_putPolicy = new Qiniu_RS_PutPolicy ( NH_QINIU_BUCKET );
        $str_upToken = $obj_putPolicy->Token ( null );
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1; 
        //user photo name
        $this->load->helper('string');
        $str_salt = random_string('alnum', 6);
        $avatar_key = 'user_avatar_'.$user_id.date('YmdHis',time()).'_i'.$str_salt;//头像图片在qiniu上的key
        $avatar_source_key = 'source_' . $avatar_key;//头像原图的key
        $result = array('success'=>false,'msg'=>'上传失败');
        //put file
        list($ret, $err) = Qiniu_PutFile($str_upToken, $avatar_key, $tmpfname, $putExtra);
        @unlink($tmpfname);
        if($err === null) {
            $result['avatar_key'] = $ret['key'];
        }
        //update db
        $update_data = array('avatar' => $result['avatar_key']);
        $this->business_user->modify_user($update_data, $user_id);
        //session
        $this->session->set_userdata('avatar', $result['avatar_key']);
        $result['success'] = true;
        $result['msg'] = '上传成功';
        return $result;
    }

    public function do_login($user_info){
        $user_id = $user_info['id']; //获取email注册用户的user_id
        if($user_id && $user_info['phone_verified']) {
            $phone = get_pnum_phone_server($user_id);
        }else{
            $phone = '';
        }
        log_message('debug_nahao', "In business_oauth, user_id is $user_id , phone is $phone");
        $reg_type = 3;
        $remb_me = 1;
        $phone_mask = $user_info['phone_mask'];
        $phone_mask = (strpos($phone_mask, '*') !== false) ? $phone_mask : phone_blur($phone_mask);
        //set session data
        $this->set_session_data($user_info['id'], $user_info['nickname'], $user_info['avatar'],
            $phone, $phone_mask, $user_info['email'], $reg_type, $user_info['teach_priv'], $remb_me);
    }
}