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
    protected $table_name = "";
    
    public function __construct($table_name = NULL)
    {
//         parent::__construct();
//         $this->load->database();
        $this->load->database();
        parent::__construct();
        
//         $this->boolMagic = get_magic_quotes_runtime();
        $table_name && $this->table_name = $table_name;
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
            $arr_config = config_item('sql_config');
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
            if ($this->input->get('a') == 'nh' AND $this->input->get('d') == 1) {
                header("Content-type: text/html; charset=utf-8");
                o($this->db->last_query());
            }
        }
        return $mix_return;
    }

    /*
     * $remb_me: 0表示不自动登录，1表示自动登录，默认自动登录
     */
    public function set_session_data($user_id, $nickname, $avatar, $phone, $phone_mask, $email, $reg_type, $user_type, $remb_me=1)
    {
        if($avatar == '') $avatar = static_url('/images/login/default_avatar.png');
        else $avatar = NH_QINIU_URL.$avatar;

        $userdata = array(
            'user_id' => $user_id,
            'nickname' => $nickname,
            'avatar' => $avatar,
            'phone' => $phone,
            'phone_mask' => $phone_mask,
            'email' => $email,
            'reg_type' => $reg_type,
            'user_type' => $user_type, //0表示学生，1表示老师
            'remb_me' => $remb_me,
        );
        $this->session->set_userdata($userdata);


        $CI =& get_instance();

        if($remb_me)
        {
            $expire = $CI->config->item('sess_autologin_expiration') + time();
        }
        else
        {
            $expire = $CI->config->item('sess_expiration') + time();
        }

        $CI->load->model('model/common/model_session_log', 'msl');
        $cookie_name = $CI->config->item('sess_cookie_name');
        $session_log = array(
            'session_id' => $_COOKIE[$cookie_name],
            'user_id' => $user_id,
            'nickname' => $nickname,
            'ip' => ip2long($CI->input->ip_address()),
            'expire_time' => $expire,
            'user_type' => $user_type,
            'exit_time' => 0,
        );

        log_message('debug_nahao', 'NH_Model/set_session_data, '.print_r($session_log,1));
        $CI->msl->update_session_log($session_log);
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
        $email_count = $this->model_user->get_user_by_param('user', 'count', '*', array('email'=>$email, 'status'=>1));
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
        if(!check_name_length($nickname))
        {
            return $this->_log_reg_info(ERROR, 'reg_invalid_nickname', array('nickname'=>$nickname,'error'=>'nickname_length_abnormal'));
        }

        //check unique
        $nickname_count = $this->model_user->get_user_by_param('user', 'count', '*', array('nickname'=>$nickname, 'status'=>1));
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
    
    /**
     * @desc 根据id找到一个表里面的某条数据
     * @param int 
     * @return array() $ret
     * @example T(TABLE_USER)->getById(1);
     * @author shuaiqi_zhang
     */
    function getById($id) 
    {
    	$ret = false;
    
    	if (empty($id)) {
    		return false;
    	}
    
    	$this->db->where('id', $id);
    	$query = $this->db->get($this->table_name);
    	if (!empty($query) && $query->num_rows() > 0) {
    			
    		$ret = $query->row_array();
    			
    	}
    	return $ret;
    }
    
    /**
     * @desc 根据条件找到一个表里面的符合条件数据的数量
     * @param string
     * @return array() $ret
     * @example T(TABLE_USER)->count("id = 1 and name = 'zhangsan'");
     * @author shuaiqi_zhang
     */
    function count($where = "") {
    	$ret = 0;
    
    	$where = trim($where);
    
    	if (empty($where)) {
    		$where = "1=1";
    	}
    
    	$this->db->where($where, NULL, false);
    
    	$this->db->from($this->table_name);
    
    	$ret = $this->db->count_all_results();
    
    	return $ret;
    }
    
    /**
     * @desc 根据列的值找一条数据
     * @param string $columnname,string $value
     * @return array() $ret
     * @example T(TABLE_USER)->getOneRowByColumn('id','1');
     * @author shuaiqi_zhang
     */
    function getOneRowByColumn($columnname, $value)
    {
    	$ret = false;
    
    	if ($value!==0 && empty($value)) {
    		return false;
    	}
    
    	$this->db->where($columnname, $value);
    	$query = $this->db->get($this->table_name);
    	if (!empty($query) && $query->num_rows() > 0) {
    		$ret = $query->row_array();
    	}
    	
    	return $ret;
    }
    
    /**
     * @desc 根据条件找一条数据
     * @param string $where
     * @return array() $ret
     * @example T(TABLE_USER)->getOneRowByWhere('id = 1');
     * @author shuaiqi_zhang
     */
    function getOneRowByWhere($where)
    {
    	$ret = false;
    
    	$this->db->where($where, NULL, false);
    	$query = $this->db->get($this->table_name);
    	if (!empty($query) && $query->num_rows() > 0) {
    		$ret = $query->row_array();
    	}
    
    	return $ret;
    }
    
    /**
     * @desc 根据条件找到一个表里面的符合条件数据
     * @param string $where 条件
     * @param int $offset start,int $perpage
     * @param string $order_by e.g. 'title desc, name asc'
     * @param boolean $distinct
     * @return array() $ret
     * @example T(TABLE_USER)->getAll("status = 1",0,5,'nickname desc');
     * @author shuaiqi_zhang
     */
    function getAll($where = "", $offset = 0, $perpage = 0, $order_by = "", $distinct = false) 
    {
    	$ret = array();
    	$where = trim($where);
    
    	if (empty($where)) {
    		$where = "1=1";
    	}
    
    	$this->db->where($where, NULL, false);
    
    	if ($perpage > 0) {
    		$this->db->limit($perpage, $offset);
    	}
    
    	if (!empty($order_by)) {
    		$this->db->order_by($order_by);
    	}
    
    	if($distinct){
    		$this->db->distinct();
    	}
    	$query = $this->db->get($this->table_name);
    
    	if (!empty($query) && $query->num_rows() > 0) {
    		$ret = $query->result_array();
    	}
    
    	return $ret;
    }
    
    /**
     * @desc 根据条件找到一个表里面的符合条件的某些列的数据
     * @param array() $cols 列字段
     * @param string $where 条件
     * @param int $offset start,int $perpage
     * @param string $order_by e.g. 'title desc, name asc'
     * @param boolean $distinct
     * @return array() $ret
     * @example T(TABLE_USER)->getFields(array('id','nickname'),"status = 1",0,5,'nickname desc');
     * @author shuaiqi_zhang
     */
    function getFields($cols, $where = "", $offset = 0, $perpage = 0, $order_by = "", $distinct = false) 
    {
    
    	$ret = array();
    
    	$where = trim($where);
    
    	if (empty($where)) {
    		$where = "1=1";
    	}
    
    	foreach($cols as $col){
    		$this->db->select($col);
    	}
    
    	$this->db->where($where, NULL, false);
    
    	if ($perpage > 0) {
    		$this->db->limit($perpage, $offset);
    	}
    
    	if (!empty($order_by)) {
    		$this->db->order_by($order_by);
    	}
    
    	if($distinct){
    		$this->db->distinct();
    	}
    	$query = $this->db->get($this->table_name);
    
    	if (!empty($query) && $query->num_rows() > 0) {
    		$ret = $query->result_array();
    	}
    
    	return $ret;
    }
    
    /**
     * @desc 插入数据，id自增,可以传入对象或者数组,传入对象则返回对象，传入数组则返回数组
     * @param array()|obj $obj
     * @return array()|obj $ret
     * @example T(TABLE_USER)->add(array('status'=>1,'nickname'=>'zhangsan'));
     * @author shuaiqi_zhang
     */
    function add($obj) {
    
    	if (is_array($obj)) {
    		foreach($obj as $name => $value) {
    			if (is_string($value)) {
    				$obj[$name] = stripslashes($value);
    				$obj[$name] = addslashes($obj[$name]);
    			}
    		}
    	} else {
    		$vars = get_object_vars($obj);
    		foreach($vars as $name => $value) {
    
    			if (is_string($value)) {
    				$obj->$name = stripslashes($value);
    				$obj->$name = addslashes($obj->$name);
    			}
    
    		}
    	}
    
    	$this->db->insert($this->table_name, $obj);
    
    	if($this->db->affected_rows() > 0) {
    		if (is_array($obj)) {
    			$obj['id'] = $this->db->insert_id();
    		}else{
    			$obj->id = $this->db->insert_id();
    		}
    			
    		return $obj;
    	} else {
    		log_message('error_nahao',$this->table_name.": insert return false");
    		return false;
    	}
    }
    
    /**
     * @desc 根据id删除一条数据
     * @param int $id
     * @return boolean 
     * @example T(TABLE_USER)->delete(1);
     * @author shuaiqi_zhang
     */
    function delete($id) 
    {
    	if (empty($id)) {
    		return false;
    	}
    
    	$this->db->where('id', $id);
    	$this->db->delete($this->table_name);
    
    	if($this->db->affected_rows() > 0) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * @desc 根据条件删除某些数据
     * @param int $id
     * @return boolean
     * @example T(TABLE_USER)->deleteByWhere('id = 1');
     * @author shuaiqi_zhang
     */
    function deleteByWhere($where) 
    {
    	if (empty($where)) {
    		return false;
    	}
    
    	$this->db->where($where, NULL, false);
    	$this->db->delete($this->table_name);
    
    	if($this->db->affected_rows() > 0) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * @desc 根据id更新某条数据
     * @param int $id,array()|obj $obj
     * @return boolean
     * @example T(TABLE_USER)->update(1,array("nickname" =>"zhangsan"));
     * @author shuaiqi_zhang
     */
    function update($id, $obj) 
    {
    	if (empty($id) || empty($obj)) {
    		return false;
    	}
    
    	if (is_array($obj)) {
    		foreach($obj as $name => $value) {
    			if (is_string($value)) {
    				$obj[$name] = stripslashes($value);
    				$obj[$name] = addslashes($obj[$name]);
    			}
    		}
    	} else {
    		$vars = get_object_vars($obj);
    		foreach($vars as $name => $value) {
    			if (is_string($value)) {
    				$obj->$name = stripslashes($value);
    				$obj->$name = trim(addslashes($obj->$name));
    			}
    		}
    	}
    
    	$this->db->where('id', $id);
    	$this->db->update($this->table_name, $obj);
    
    	if($this->db->affected_rows() > 0) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * @desc 根据条件更新某条数据
     * @param string $where,array()|obj $obj
     * @return boolean
     * @example T(TABLE_USER)->updateByWhere("id = 1",array("nickname" =>"zhangsan"));
     * @author shuaiqi_zhang
     */
    function updateByWhere($where, $obj) 
    {
    	if (empty($where) || empty($obj)) {
    		return false;
    	}
    
    	$this->db->where($where, NULL, false);
    	$this->db->update($this->table_name, $obj);
    
    	if($this->db->affected_rows() > 0) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * @desc 执行一条sql
     * @param string $sql
     * @return array()
     * @example T(TABLE_USER)->executeQuery("select id,nickname from user where id=1");
     * @author shuaiqi_zhang
     */
    function executeQuery($sql) 
    {
    	if (empty($sql)) {
    		return array();
    	}
    
    	$query = $this->db->query($sql);
    
    	if (!empty($query) && $query->num_rows() >0 ) {
    		return $query->result_array();
    	}
    
    	return array();
    }
    
    
    
}