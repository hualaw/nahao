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
        $str_table_range = 'teacher_info_subject';
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

        $str_table_range = 'teacher_info_subject';
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
            $str_table_range = 'teacher_info_subject';
            $str_result_type = 'list';
            $str_fields = TABLE_USER.'.id';
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
            $arr_group_by = array(
                TABLE_USER.'.id'
            );
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where,$arr_group_by);
            $int_return = count($arr_return);
//            echo '--';
//            var_dump($int_return);
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
            $str_table_range = 'teacher_info_subject';
            $str_result_type = 'list';
//            $str_fields = TABLE_USER.'.id,nickname,phone_mask,email,'.TABLE_USER.'.status,source,gender,grade,province,city,area';
            $str_fields = TABLE_USER.'.id,nickname,phone_mask,email,gender,realname,,source,gender,grade,province,city,area,teacher_age,title,stage,register_time,'.TABLE_NAHAO_AREAS.'.name,'.TABLE_USER.'.status,'.TABLE_SUBJECT.'.name as subject_name';
            $arr_where[TABLE_USER.'.teach_priv'] = TABLE_USER_DIC_TEACH_PRIV_ON;
//            $arr_where[TABLE_USER.'.status'] = TABLE_USER_DIC_STATUS_ON;
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
            $arr_order_by=array(
                TABLE_USER.'.register_time'=>'desc'
            );
           // var_dump($arr_where);
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, $arr_order_by,$arr_limit);
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
    public function check_nick_name($nickname,$user_id)
    {
        if(!check_name_length($nickname))
        {
            return 2;
        }
        return $this->model_user->check_nick($nickname,$user_id);
    }
    /**
     * 真实姓名长度
     * @author shangshikai@tizi.com
     */
     public function check_real_name($realname)
     {
         if(!check_name_length($realname))
         {
             return 2;
         }
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
     * 修改教师
     * @author shangshikai@tizi.com
     */
    public function teacher_momdify($user_id)
    {
        return $this->model_user->modify_teacher($user_id);
    }
    /**
     * 验证添加教师表单
     * @author shangshikai@tizi.com
     */
    public function check_post($post)
    {
        $post_user=array();
        $post_user_info=array();
        $post_subject=array();
        //var_dump($post);die;
        $post['nickname']=trim($post['nickname']);
        $post['password']=trim($post['password']);
        $post['realname']=trim($post['realname']);
        $post['age']=trim($post['age']);
        $post['school']=trim($post['school']);
        $post['basic_reward']=trim($post['basic_reward']);
        $post['phone_mask']=trim($post['phone_mask']);
        $post['email']=trim($post['email']);
        $post['teacher_intro']=htmlspecialchars(trim($post['teacher_intro']));
        $post['bank_id']=trim($post['bank_id']);
        $post['id_card']=trim($post['id_card']);
        $post['bank_Branch']=trim($post['bank_Branch']);

        if($post['hide_school']==null)
        {
            $post['hide_school']=0;
        }
        if($post['hide_realname']==null)
        {
            $post['hide_realname']=0;
        }
        if($post['city']==null)
        {
            $post['city']=0;
        }
        if($post['area']==null)
        {
            $post['area']=0;
        }
       // var_dump($post);die;
       // var_dump($post['nickname']);die;
        if($post['nickname']=="" || $post['password']=="" || $post['realname']=="" || $post['basic_reward']=="" || $post['phone_mask']=="" || $post['email']=="" || $post['age']=="" || !is_numeric($post['basic_reward']) || !is_numeric($post['age']) || $post['basic_reward']<0 || $post['age']<20  || $post['age']>100 || !check_name_length($post['nickname']) || !check_name_length($post['realname']))
        {
            redirect("teacher/create");
        }

        $nickname_count=mb_strlen($post['nickname'],'utf8');
        $password_count=mb_strlen($post['password'],'utf8');
        $realname_count=mb_strlen($post['realname'],'utf8');
        //echo $nickname_count,'<br />',$password_count,'<br />',$realname_count;die;
        if($nickname_count < 2 || $nickname_count > 15 || $password_count < 6 || $password_count > 20 || $realname_count < 2 || $realname_count > 15)
        {
            redirect('teacher/create');
        }

        $nick=$this->model_user->check_nick($post['nickname']);
        if($nick=="yes")
        {
            redirect('teacher/create');
        }

        $tel=$this->model_user->check_tel($post['phone_mask']);
        if($tel!=NULL)
        {
            redirect('teacher/create');
        }

        $ema=$this->model_user->check_tec_email($post['email']);
        if($ema!=NULL)
        {
            redirect('teacher/create');
        }
        $phone_mask=substr_replace($post['phone_mask'],"****",3,4);
        $post_user['nickname']=$post['nickname'];
        $post_user['phone_mask']=$phone_mask;
        $post_user['email']=$post['email'];
        $post_user['salt']=random_string('alnum',6);
        $post_user['password']=create_password($post_user['salt'],$post['password']);
        $post_user['status']=1;
        $post_user['register_time']=time();
        $post_user['register_ip']=ip2long($this->input->ip_address());
        $post_user['phone_verified']=1;
        $post_user['email_verified']=1;
        $post_user['teach_priv']=1;
        $post_user['source']=1;
       // var_dump($post_user);die;
        $user_id=$this->model_user->create_user($post_user);
        if($user_id!=0)
        {
            add_user_phone_server($user_id,$post['phone_mask']);
        }
        //var_dump($user_id);die;
        $post_subject['subject_id']=$post['subject'];
        $post_subject['teacher_id']=$user_id;
        $this->model_user->create_subject($post_subject);

        $post['teacher_auth_img']=="" ? $post_user_info['teacher_auth']=0 : $post_user_info['teacher_auth']=1;
        $post['title_auth_img']=="" ? $post_user_info['titile_auth']=0 : $post_user_info['titile_auth']=1;
        $post['work_auth_img']=="" ? $post_user_info['work_auth']=0 : $post_user_info['work_auth']=1;
        $post_user_info['user_id']=$user_id;
        $post_user_info['realname']=$post['realname'];
        $post_user_info['age']=$post['age'];
        $post_user_info['gender']=$post['gender'];
        $post_user_info['hide_realname']=$post['hide_realname'];
        $post_user_info['hide_school']=$post['hide_school'];
        $post_user_info['bankname']=$post['bank'];
        $post_user_info['bankbench']=$post['bank_Branch'];
        $post_user_info['bankcard']=$post['bank_id'];
        $post_user_info['id_code']=$post['id_card'];
        $post_user_info['title']=$post['title'];
        $post_user_info['province']=$post['province'];
        $post_user_info['city']=$post['city'];
        $post_user_info['area']=$post['area'];
        $post_user_info['school']=$post['school'];
        $post_user_info['teacher_age']=$post['teacher_age'];
        $post_user_info['stage']=$post['stage'];
        $post_user_info['teacher_intro']=$post['teacher_intro'];
        $post_user_info['basic_reward']=$post['basic_reward'];
        $post_user_info['work_auth_img']=$post['work_auth_img'];
        $post_user_info['title_auth_img']=$post['title_auth_img'];
        $post_user_info['teacher_auth_img']=$post['teacher_auth_img'];
        $post_user_info['status']=1;
        //var_dump($post_user_info);die;
       // var_dump($post_user_info);die;
        return $this->model_user->create_user_info($post_user_info);
    }
    /**
     * 验证修改教师表单
     * @author sahngshikai@tizi.com
     */
    public function check_edit_post($post)
    {
        $post_user=array();
        $post_user_info=array();
        $post_subject=array();
        $post_update=array();
        $post_update_id=array();
        $post['nickname']=trim($post['nickname']);
        $post['realname']=trim($post['realname']);
        $post['age']=trim($post['age']);
        $post['school']=trim($post['school']);
        $post['basic_reward']=trim($post['basic_reward']);
        $post['teacher_intro']=htmlspecialchars(trim($post['teacher_intro']));
        $post['bank_id']=trim($post['bank_id']);
        $post['id_card']=trim($post['id_card']);
        $post['bank_Branch']=trim($post['bank_Branch']);
        $nickname_count=mb_strlen($post['nickname'],'utf8');
        //var_dump($post);die;
        if(!isset($post['hide_school']))
        {
            $post['hide_school']=0;
        }
        if(!isset($post['hide_realname']))
        {
            $post['hide_realname']=0;
        }
        if(!isset($post['city']))
        {
            $post['city']=0;
        }
        if(!isset($post['area']))
        {
            $post['area']=0;
        }
        //var_dump($post);die;
        //var_dump($post);die;
        if($post['nickname']=="" || $nickname_count < 2 || $nickname_count > 15 || $post['realname']=="" || $post['basic_reward']=="" || $post['age']=="" || !is_numeric($post['basic_reward']) || !is_numeric($post['age']) || $post['basic_reward']<0 || $post['age']<20  || $post['age']>100 || !check_name_length($post['realname']))
        {
            redirect("teacher/modify?user_id=$post[user_id]");
        }

        $nick=$this->model_user->check_nick($post['nickname'],$post['user_id']);
        if($nick=="yes")
        {
            redirect("teacher/modify?user_id=$post[user_id]");
        }
        $post_subject['subject_id']=$post['subject'];
        $post['teacher_auth_img']=="" ? $post_user_info['teacher_auth']=0 : $post_user_info['teacher_auth']=1;
        $post['title_auth_img']=="" ? $post_user_info['titile_auth']=0 : $post_user_info['titile_auth']=1;
        $post['work_auth_img']=="" ? $post_user_info['work_auth']=0 : $post_user_info['work_auth']=1;

        $post_user_info['realname']=$post['realname'];
        $post_user_info['age']=$post['age'];
        $post_user_info['gender']=$post['gender'];
        $post_user_info['hide_realname']=$post['hide_realname'];
        $post_user_info['hide_school']=$post['hide_school'];
        $post_user_info['bankname']=$post['bank'];
        $post_user_info['bankbench']=$post['bank_Branch'];
        $post_user_info['bankcard']=$post['bank_id'];
        $post_user_info['id_code']=$post['id_card'];
        $post_user_info['title']=$post['title'];
        $post_user_info['province']=$post['province'];
        $post_user_info['city']=$post['city'];
        $post_user_info['area']=$post['area'];
        $post_user_info['school']=$post['school'];
        $post_user_info['teacher_age']=$post['teacher_age'];
        $post_user_info['stage']=$post['stage'];
        $post_user_info['teacher_intro']=$post['teacher_intro'];
        $post_user_info['basic_reward']=$post['basic_reward'];
        $post_user_info['work_auth_img']=$post['work_auth_img'];
        $post_user_info['title_auth_img']=$post['title_auth_img'];
        $post_user_info['teacher_auth_img']=$post['teacher_auth_img'];
        $post_user_info['update_time']=time();

        if($post_user_info['hide_school']==null)
        {
            unset($post_user_info['hide_school']);
        }
        if($post_user_info['hide_realname']==null)
        {
            unset($post_user_info['hide_realname']);
        }
        if($post_user_info['work_auth']==null)
        {
            unset($post_user_info['work_auth']);
        }
        if($post_user_info['teacher_auth']==null)
        {
            unset($post_user_info['teacher_auth']);
        }
        if($post_user_info['titile_auth']==null)
        {
            unset($post_user_info['titile_auth']);
        }
        if($post_user_info['city']==null || $post_user_info['city']==1)
        {
            unset($post_user_info['city']);
        }
        if($post_user_info['area']==null || $post_user_info['area']==1)
        {
            unset($post_user_info['area']);
        }
        $post_update['user_id']=$post['user_id'];
        $post_update_id['id']=$post['user_id'];
        //var_dump($post['user_id']);die;
        $post_user['nickname']=$post['nickname'];
        if($this->model_user->modify_subject($post_subject,$post['user_id']) && $this->model_user->update_user($post_user,$post_update_id))
        {
            return $this->model_user->update_user_info($post_user_info,$post_update);
        }
    }
    /**
     * 根据城市查找学校
     * @author shangshikai@tizi.com
     */
    public function school_business_pid($school_id)
    {
        return $this->model_user->school_model_pid($school_id);
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