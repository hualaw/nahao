<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Student相关逻辑
 * Class Business_Student
 * @author yanrui@tizi.com
 */
class Business_Student extends NH_Model
{
    /**
     * 查询时 如果arr_where参数中有以下任意字段，那么就连user_info表，不包含这些就直接查user表
     * @var array
     * @author yanrui@tizi.com
     */
//    private $arr_search_fields = array(
//        'stage','province','course_type','subject','gender','has_bought'
//    );

    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_user');
    }

    /**
     * 修改student
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_student($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_user->update_user($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取student count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_student_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'student_info';
            $str_result_type = 'list';
            $str_fields = 'user.id';

            if(array_key_exists('stage',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.stage'] = $arr_where['stage'];
                unset($arr_where['stage']);
            }
            if(array_key_exists('province',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.province'] = $arr_where['province'];
                unset($arr_where['province']);
            }
            if(array_key_exists('course_type',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.course_type'] = $arr_where['course_type'];
                unset($arr_where['course_type']);
            }
            if(array_key_exists('gender',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.gender'] = $arr_where['gender'];
                unset($arr_where['gender']);
            }
            if(array_key_exists('has_bought',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.has_bought'] = $arr_where['has_bought'];
                unset($arr_where['has_bought']);
            }
            if(array_key_exists('subject',$arr_where)){
                $arr_where[TABLE_STUDENT_SUBJECT.'.subject_id'] = $arr_where['subject'];
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
            $arr_group_by = array(
                TABLE_USER.'.id'
            );
            $arr_order_by = array(
                TABLE_USER.'.register_time' => 'desc'
            );
//            o($arr_where);
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, $arr_order_by);
            $int_return = count($arr_return);
        }
        return $int_return;
    }

    /**
     * 根据条件获取student list
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_student_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'student_info';
            $str_result_type = 'list';
            $str_fields = TABLE_USER.'.id,nickname,phone_mask,email,'.TABLE_USER.'.status,source,gender,grade,province,city,area,register_time';
            $arr_where[TABLE_USER.'.teach_priv'] = TABLE_USER_DIC_TEACH_PRIV_OFF;
            if(array_key_exists('stage',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.stage'] = $arr_where['stage'];
                unset($arr_where['stage']);
            }
            if(array_key_exists('province',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.province'] = $arr_where['province'];
                unset($arr_where['province']);
            }
            if(array_key_exists('course_type',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.course_type'] = $arr_where['course_type'];
                unset($arr_where['course_type']);
            }
            if(array_key_exists('gender',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.gender'] = $arr_where['gender'];
                unset($arr_where['gender']);
            }
            if(array_key_exists('has_bought',$arr_where)){
                $arr_where[TABLE_USER_INFO.'.has_bought'] = $arr_where['has_bought'];
                unset($arr_where['has_bought']);
            }
            if(array_key_exists('subject',$arr_where)){
                $arr_where[TABLE_STUDENT_SUBJECT.'.subject_id'] = $arr_where['subject'];
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
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_group_by = array(
                TABLE_USER.'.id'
            );
            $arr_order_by = array(
                TABLE_USER.'.register_time' => 'desc'
            );
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, $arr_order_by,$arr_limit);
        }
        return $arr_return;
    }

    /**
     * 根据id取student
     * @param $int_student_id
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_student_by_id($int_student_id)
//    {
//        $arr_return = array();
//        if($int_student_id){
//            $str_table_range = 'student';
//            $str_result_type = 'one';
//            $str_fields = '*';
//            $arr_where = array(
//                'id' => $int_student_id
//            );
//            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
//        }
//        return $arr_return;
//    }

    /**
     * 根据username取student
     * @param string $str_username
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_student_by_username($str_username)
//    {
//        $arr_return = array();
//        if($str_username){
//            $str_table_range = 'student';
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