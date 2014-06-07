<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 那好Model超级父类
 * @author yanrui@91waijiao.com
 */
class NH_Model extends CI_Model
{
    /**
     * @var 判断get_magic_quotes_runtime
     */
    public $boolMagic;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
//        $this->boolMagic = get_magic_quotes_runtime();
    }

    /**
     * 读取配置拿到表，去数据库中查询
     * @param string $str_table_range
     * @param string $str_result_type
     * @param string $str_field
     * @param array $arr_where
     * @param array $arr_group_by
     * @param array $arr_order_by
     * @param array $arr_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function _get_from_db($str_table_range = 'admin', $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = array();
//        echo $str_table_range.'--'.$str_result_type.'--'.$str_field."\n";echo "where : \n";var_dump($arr_where);echo "group_by : \n";var_dump($arr_group_by);echo "order_by : \n";var_dump($arr_order_by);echo "limit : \n";var_dump($arr_limit);//exit;
        if (is_array($arr_where)) {
            $arr_config = config_item('sql_' . DOMAIN);
            if (array_key_exists($str_table_range, $arr_config)) {
                $arr_keys = array_keys($arr_config[$str_table_range]);
                $arr_keys = array_flip($arr_keys);
                foreach ($arr_config[$str_table_range] as $k => $v) {
                    if ($arr_keys[$k] == 0) {
                        $this->db->from($k);
                    } else {
                        $this->db->join($k, $v[0], $v[1]);
                    }
                }
                $this->db->select($str_field);
                if (!empty($arr_where)) {
                    if (isset($arr_where['like'])) {
                        $arr_like = $arr_where['like'];
                        unset($arr_where['like']);
                    }
                    foreach ($arr_where as $k => $v) {
                        if (is_array($v)) {
                            $this->db->where_in($k, $v);
                        } else {
                            $this->db->where($k, $v);
                        }
                    }
//                    if ($arr_like) {
                    if (isset($arr_like) AND $arr_like) {
                        foreach ($arr_like as $k => $v) {
                            $this->db->like($k, $v);
                        }
                    }
                }
                if (!empty($arr_group_by)) {
                    foreach ($arr_group_by as $value) {
                        $this->db->group_by($value);
                    }
                }
                if (!empty($arr_order_by)) {
                    foreach ($arr_order_by as $k => $v) {
                        $str_order = $v ? $v : 'DESC';
                        $this->db->order_by($k, $str_order);
                    }
                }
                if (!empty($arr_limit)) {
                    $this->db->limit($arr_limit['limit'], $arr_limit['start']);
                }
                if ($str_result_type == 'count') {
                    $mix_return = $this->db->count_all_results();
                } elseif ($str_result_type == 'one') {
                    $mix_return = $this->db->get()->row_array();
                } elseif ($str_result_type == 'list') {
                    $mix_return = $this->db->get()->result_array();
                }
            } else {
                die('no such table range : ' . $str_table_range);
            }
            if ($this->input->get('d') == 1) {
                header("Content-type: text/html; charset=utf-8");
                o($this->db->last_query());
            }
        }
        return $mix_return;
    }

    public function set_session_data($user_id, $nickname, $avatar, $phone, $phone_mask, $email)
    {

        if($nickname == '' )
        {
            if($phone_mask)  $nickname = $phone_mask;
            else if($email) $nickname = $email;
        }
        else
        {
            $info_arr = array(
                'user_id'=>$user_id,
                'nickname' => $nickname,
                'avatar' => $avatar,
                'phone' => $phone,
                'phone_mask' => $phone_mask,
                'email' => $email,
            );
            $this->_log_reg_info(ERROR, 'reg_no_nickname', $info_arr);
        }

        if($avatar == '') $avatar = static_url('/images/login/default_avatar.png');

        $userdata = array(
            'user_id' => $user_id,
            'nickname' => $nickname,
            'avatar' => $avatar,
            'phone' => $phone,
            'phone_mask' => $phone_mask,
            'email' => $email,
        );
        $this->session->set_userdata($userdata);
    }

    public function check_phone_format($phone)
    {
        //check phone is invalid
        if(!is_mobile($phone))
        {
            return $this->_log_reg_info(ERROR, 'rl_invalid_phone', array('phone'=>$phone));
        }

        return $this->_log_reg_info(SUCCESS, 'rl_check_phone_success', array('phone'=>$phone));
    }

    public function check_phone_unique($phone)
    {
        //check phone is unique
        $user_id = get_uid_phone_server($phone);

        if($user_id)
        {
            return $this->_log_reg_info(ERROR, 'reg_dup_phone', array('phone'=>$phone));
        }

        return $this->_log_reg_info(SUCCESS, 'rl_check_phone_success', array('phone'=>$phone));
    }

    public function check_email_format($email)
    {
        if(!is_email($email))
        {
            return $this->_log_reg_info(ERROR, 'rl_invalid_email', array('email'=>$email));
        }

        return $this->_log_reg_info(SUCCESS, 'rl_check_email_success', array('email'=>$email));
    }

    public function check_email_unique($email)
    {
        //check email is unique
        $email_count = $this->model_user->get_user_by_param('user', 'count', '*', array('email'=>$email));
        if($email_count >= 1)
        {
            return $this->_log_reg_info(ERROR, 'reg_dup_email', array('email'=>$email));
        }

        return $this->_log_reg_info(SUCCESS, 'rl_check_email_success', array('email'=>$email));
    }

    public function check_phone($phone)
    {
        $arr_ret = $this->check_phone_format($phone);
        if($arr_ret['status'] != SUCCESS) return $arr_ret;

        $arr_ret = $this->check_phone_unique($phone);
        return $arr_ret;
    }

    public function check_email($email)
    {
        $arr_ret = $this->check_email_format($email);
        if($arr_ret['status'] != SUCCESS) return $arr_ret;

        $arr_ret = $this->check_email_unique($email);
        return $arr_ret;
    }

    public function check_nickname($nickname)
    {
        //check length
        $len = strlen($nickname);
        if($len < MIN_NICKNAME_LEN*3 || $len > MAX_NICKNAME_LEN*3)
        {
            return $this->_log_reg_info(ERROR, 'reg_invalid_nickname', array('nickname'=>$nickname));
        }

        //check unique
        $nickname_count = $this->model_user->get_user_by_param('user', 'count', '*', array('nickname'=>$nickname));
        if($nickname_count >= 1)
        {
            return $this->_log_reg_info(ERROR, 'reg_dup_nickname', array('nickname'=>$nickname));
        }
        return $this->_log_reg_info(SUCCESS, 'reg_check_nickname_success', array('nickname'=>$nickname), 'info');
    }

    function _log_reg_info($status, $msg_type, $info_arr=array(), $info_type='error')
    {
        $arr_return['status'] = $status;
        $arr_return['msg'] = $this->lang->line($msg_type);
        $arr_return['data'] = $info_arr;
        switch($info_type)
        {
            case 'error':
                log_message('error_nahao', json_encode($arr_return));
                break;
            case 'info':
                log_message('info_nahao', json_encode($arr_return));
                break;
            case 'debug':
                log_message('debug_nahao', json_encode($arr_return));
                break;
        }
        return $arr_return;
    }
}