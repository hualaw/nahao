<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Teacher相关逻辑
 * Class Business_Teacher
 * @author yanrui@tizi.com
 */
class Business_Teacher extends NH_Model
{

    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_user');
    }

    /**
     * 获取所有教师总数teacher count
     * @param
     * @return
     * @author shangshikai@tizi.com
     */
    public function total_count()
    {
        $str_table_range = 'teacher_info2';
        $str_result_type = 'count';
        $str_fields = 'count(1) as count';
        $arr_where[TABLE_USER.'.teach_priv'] = 1;
        $int_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        return $int_return;
    }

    /**
     * 获取今日申请教师总数teacher count
     * @param
     * @return
     * @author shangshikai@tizi.com
     */
    public function day_count()
    {
        $day=date('Y-m-d',time());
        $day_start=strtotime($day." 00:00:00");
        $day_end=strtotime($day." 23:59:59");

        $str_table_range = 'teacher_info2';
        $str_result_type = 'count';
        $str_fields = 'count(1) as count';
        $arr_where[TABLE_USER.'.teach_priv'] = 1;
        $arr_where[TABLE_USER.'.register_time<'] = $day_end;
        $arr_where[TABLE_USER.'.register_time>'] = $day_start;
        $int_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        return $int_return;
    }
    /**
     * 根据条件获取teacher count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_teacher_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'teacher_info2';
            $str_result_type = 'count';
            $str_fields = 'count(1) as count';
            $arr_where[TABLE_USER.'.teach_priv'] = 1;
            if(array_key_exists('title',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.title'] = $arr_where['title']-1;
                unset($arr_where['title']);
            }
            if(array_key_exists('province',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.province'] = $arr_where['province'];
                unset($arr_where['province']);
            }
            if(array_key_exists('phone_mask',$arr_where)){
                $arr_where[TABLE_USER.'.phone_mask'] = $arr_where['phone_mask'];
                unset($arr_where['phone_mask']);
            }
            if(array_key_exists('gender',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.gender'] = $arr_where['gender'];
                unset($arr_where['gender']);
            }
            if(array_key_exists('account',$arr_where)){
                $arr_where[TABLE_USER.'.status'] = $arr_where['account']-1;
                unset($arr_where['account']);
            }
            if(array_key_exists('realname',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.realname'] = $arr_where['realname'];
                unset($arr_where['realname']);
            }
            if(array_key_exists('subject',$arr_where)){
                $arr_where[TABLE_TEACHER_SUBJECT.'.subject_id'] = $arr_where['subject'];
                unset($arr_where['subject']);
            }
            if(array_key_exists('nickname',$arr_where)){
                $arr_where['like'][TABLE_USER.'.nickname '] = $arr_where['nickname'];
                unset($arr_where['nickname']);
            }
            if(array_key_exists('email',$arr_where)){
                $arr_where['like'][TABLE_USER.'.email '] = $arr_where['email'];
                unset($arr_where['email']);
            }
            if(array_key_exists('id',$arr_where)){
                $arr_where[TABLE_USER.'.id'] = $arr_where['id'];
                unset($arr_where['id']);
            }
            if(array_key_exists('add_time1',$arr_where) && array_key_exists('add_time2',$arr_where)){
                $arr_where['add_time1']=strtotime($arr_where['add_time1']);
                $arr_where['add_time2']=strtotime($arr_where['add_time2']);
                $arr_where[TABLE_USER.'.register_time >'] = $arr_where['add_time1'] ;
                $arr_where[TABLE_USER.'.register_time <'] = $arr_where['add_time2'] ;
                unset($arr_where['add_time1']);
                unset($arr_where['add_time2']);
             }
//            o($arr_where);
            $int_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $int_return;
    }

    /**
     * 根据条件获取teacher list
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_teacher_list($arr_where,$int_start,$int_limit){
        //var_dump($arr_where);die;
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'teacher_info2';
            $str_result_type = 'list';
            $str_fields = TABLE_USER.'.id,nickname,phone_mask,email,gender,realname,teacher_age,title,stage,register_time,nahao_areas.name,user.status,subject.name as sub_name';

            $arr_where[TABLE_USER.'.teach_priv'] = 1;
            if(array_key_exists('title',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.title'] = $arr_where['title']-1;
                unset($arr_where['title']);
            }
            if(array_key_exists('province',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.province'] = $arr_where['province'];
                unset($arr_where['province']);
            }
            if(array_key_exists('phone_mask',$arr_where)){
                $arr_where[TABLE_USER.'.phone_mask'] = $arr_where['phone_mask'];
                unset($arr_where['phone_mask']);
            }
            if(array_key_exists('gender',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.gender'] = $arr_where['gender'];
                unset($arr_where['gender']);
            }
            if(array_key_exists('account',$arr_where)){
                $arr_where[TABLE_USER.'.status'] = $arr_where['account']-1;
                unset($arr_where['account']);
            }
            if(array_key_exists('realname',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.realname'] = $arr_where['realname'];
                unset($arr_where['realname']);
            }
            if(array_key_exists('subject',$arr_where)){
                $arr_where[TABLE_TEACHER_SUBJECT.'.subject_id'] = $arr_where['subject'];
                unset($arr_where['subject']);
            }
            if(array_key_exists('nickname',$arr_where)){
                $arr_where['like'][TABLE_USER.'.nickname '] = $arr_where['nickname'];
                unset($arr_where['nickname']);
            }
            if(array_key_exists('email',$arr_where)){
                $arr_where['like'][TABLE_USER.'.email '] = $arr_where['email'];
                unset($arr_where['email']);
            }
            if(array_key_exists('id',$arr_where)){
                $arr_where[TABLE_USER.'.id'] = $arr_where['id'];
                unset($arr_where['id']);
            }
            if(array_key_exists('add_time1',$arr_where) && array_key_exists('add_time2',$arr_where)){
                $arr_where['add_time1']=strtotime($arr_where['add_time1']);
                $arr_where['add_time2']=strtotime($arr_where['add_time2']);
                $arr_where[TABLE_USER.'.register_time >'] = $arr_where['add_time1'] ;
                $arr_where[TABLE_USER.'.register_time <'] = $arr_where['add_time2'] ;
                unset($arr_where['add_time1']);
                unset($arr_where['add_time2']);
            }
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_group_by = array(
                TABLE_USER.'.id'
            );
           // var_dump($arr_where);
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, array(),$arr_limit);
        }
        return $arr_return;
    }

    /**
     * teacher账户禁用
     * @author shangshikai@tizi.com
     */
    public function close_ban($arr)
    {
        $arr=explode(',',$arr);
        return $this->model_user->account_close($arr);
    }
    /**
     * teacher账户启用
     * @author shangshikai@tizi.com
     */
    public function open_ban($arr)
    {
        $arr=explode(',',$arr);
        return $this->model_user->account_open($arr);
    }
    /**
     * 根据省ID获取市
     * @author shangshikai@tizi.com
     */
    public function city1($province)
    {
        if($province==2)
        {
            return $this->model_user->city2(52);
        }
        elseif($province==25)
        {
            return $this->model_user->city2(321);
        }
        elseif($province==27)
        {
            return $this->model_user->city2(343);
        }
        elseif($province==32)
        {
            return $this->model_user->city2(394);
        }
        else
        {
            return $this->model_user->city2($province);
        }
    }
    /**
     * 根据市ID获取区
     * @author shangshikai@tizi.com
     */
    public function area1($city)
    {
        return $this->model_user->area2($city);
    }
    /**
     * 昵称是否存在
     * @author shangshikai@tizi.com
     */
    public function check_nick_name($nickname)
    {
        return $this->model_user->check_nick($nickname);
    }
    /**
     * 电话是否合法
     * @author shangshikai@tizi.com
     */
    public function check_mobile_phone($phone)
    {
        return $this->model_user->check_tel($phone);
    }
    /**
     * 邮箱是否合法
     * @author shangshikai@tizi.com
     */
    public function check_email_tec($email)
    {
        return $this->model_user->check_tec_email($email);
    }
    /**
     * 验证教师表单
     * @author shangshikai@tizi.com
     */
    public function check_post($post)
    {
        //var_dump($post);
        $post['nickname']=trim($post['nickname']);
        $post['password']=trim($post['password']);
        $post['school']=trim($post['school']);
        $post['basic_reward']=trim($post['basic_reward']);
        $post['phone_mask']=trim($post['phone_mask']);
        $post['email']=trim($post['email']);

        $preg = "/[\x{4e00}-\x{9fa5}]/u";
        if(preg_match_all($preg,$post['nickname'],$matches)){
            //print_r($matches[0]);
            $zn_count=count($matches[0]);
        }
        $preg2 = "/[^\x{4e00}-\x{9fa5}]/iu";
        if(preg_match_all($preg2,$post['nickname'],$matches)){
            //print_r($matches[0]);
            $nozn_count=count($matches[0]);
        }
        echo $zn_count+$nozn_count;
    }
    /**
     * 根据id取teacher
     * @param $int_teacher_id
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_teacher_by_id($int_teacher_id)
//    {
//        $arr_return = array();
//        if($int_teacher_id){
//            $str_table_range = 'teacher';
//            $str_result_type = 'one';
//            $str_fields = '*';
//            $arr_where = array(
//                'id' => $int_teacher_id
//            );
//            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
//        }
//        return $arr_return;
//    }

    /**
     * 根据username取teacher
     * @param string $str_username
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_teacher_by_username($str_username)
//    {
//        $arr_return = array();
//        if($str_username){
//            $str_table_range = 'teacher';
//            $str_result_type = 'one';
//            $str_fields = 'id,username,phone,email,salt,password,realname,status';
//            $arr_where = array(
//                'username' => $str_username
//            );
////            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
//            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
//        }
//        return $arr_return;
//    }

    /**
     * 获取查询的表范围(sql_key)
     * @param array $arr_where
     * @return string
     * @author yanrui@tizi.com
     */
//    public function get_table_range($arr_where){
//        $str_table_range = 'user';
//        foreach($arr_where as $k => $v){
//            if(in_array($k,$this->arr_search_fields)){
//                $str_table_range = 'user_user_info';
//                break;
//            }
//        }
//        return $str_table_range;
//    }
}