<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Round相关逻辑
 * Class Business_Round
 * @author yanrui@tizi.com
 */
class Business_Round extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_round');
    }

    /**
     * 创建round
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_round($arr_param)
    {
        $int_return = 0;
        if($arr_param){
            $arr_param['create_time'] = TIME_STAMP;
            $arr_param['role'] = NH_MEETING_TYPE_ADMIN;
            $arr_param['user_id'] = $this->userinfo['id'];
            $int_return = $this->model_round->create_round($arr_param);
        }
        return $int_return;
    }

    /**
     * 修改round
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_round($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_round->update_round($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 添加班次与老师关系
     * @param $int_round_id
     * @param $arr_teacher_ids
     * @return bool
     * @author yanrui@tizi.com
     */
    public function create_round_teacher_batch($int_round_id,$arr_teacher_ids){
        $int_return = 0;
        if($int_round_id > 0 AND is_array($arr_teacher_ids) AND $arr_teacher_ids){
            $arr_param = array();
            foreach($arr_teacher_ids as $k => $v){
                if($v > 0){
                    $arr_param[] = array(
                        'round_id' => $int_round_id,
                        'teacher_id' => $v,
                        'sequence' => $k
                    );
                }else{
                    break;
                }
            }
            if($arr_param){
                //先清除该课程以前的老师，再插入新的老师
                self::delete_teachers_by_round_id($int_round_id);
                $int_return = $this->model_round->create_round_teacher_batch($arr_param);
            }
        }
        return $int_return;
    }

    /**
     * 根据班次ID删除班次与老师的关系
     * @param $int_round_id
     * @author yanrui@tizi.com
     */
    public function delete_teachers_by_round_id($int_round_id){
        if($int_round_id > 0){
            $arr_where = array(
                'round_id' => $int_round_id
            );
            $this->model_round->delete_round_teacher_relation_by_param($arr_where);
        }
    }

    /**
     * 根据条件获取round count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_round_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'round_info';
            $str_result_type = 'list';
            $str_fields = TABLE_ROUND.'.id';
            if(array_key_exists('status',$arr_where)){
                $arr_where[TABLE_ROUND.'.status'] = $arr_where['status'];
                unset($arr_where['status']);
            }
            if(array_key_exists('subject',$arr_where)){
                $arr_where[TABLE_ROUND.'.subject'] = $arr_where['subject'];
                unset($arr_where['subject']);
            }
            if(array_key_exists('course_type',$arr_where)){
                $arr_where[TABLE_ROUND.'.course_type'] = $arr_where['course_type'];
                unset($arr_where['course_type']);
            }
            if(array_key_exists('teacher_id',$arr_where)){
                $arr_where[TABLE_ROUND.'.teacher_id'] = $arr_where['teacher_id'];
                unset($arr_where['teacher_id']);
            }
            if(array_key_exists('id',$arr_where)){
                $arr_where[TABLE_ROUND.'.id'] = $arr_where['id'];
                unset($arr_where['id']);
            }
            if(array_key_exists('title',$arr_where)){
                $arr_where['like'][TABLE_ROUND.'.title'] = $arr_where['title'];
                unset($arr_where['title']);
            }
            $arr_group_by = array(
                TABLE_ROUND.'.id'
            );
            $arr_order_by = array(
                TABLE_ROUND.'.create_time' => 'desc'
            );
            $arr_return = $this->model_round->get_round_by_param($str_table_range, $str_result_type, $str_fields, $arr_where,$arr_group_by, $arr_order_by);
            $int_return = count($arr_return);
//            o($int_return,true);
        }
        return $int_return;
    }


    /**
     * 根据条件获取round list
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_round_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'round_info';
            $str_result_type = 'list';
            $str_fields = TABLE_ROUND.'.id,title,subtitle,intro,description,students,subject,course_type,reward,price,'.TABLE_SUBJECT.'.status,create_time,'.TABLE_ROUND.'.role,user_id,score,bought_count,caps,video,img,grade_from,grade_to,sale_status,teach_status,'.TABLE_SUBJECT.'.name as subject_name,'.TABLE_COURSE_TYPE.'.name as course_type_name,'.TABLE_USER.'.nickname,class_count,sell_begin_time,sell_end_time,start_time';
//            $str_fields = '*';
            if(array_key_exists('status',$arr_where)){
                $arr_where[TABLE_ROUND.'.status'] = $arr_where['status'];
                unset($arr_where['status']);
            }
            if(array_key_exists('subject',$arr_where)){
                $arr_where[TABLE_ROUND.'.subject'] = $arr_where['subject'];
                unset($arr_where['subject']);
            }
            if(array_key_exists('course_type',$arr_where)){
                $arr_where[TABLE_ROUND.'.course_type'] = $arr_where['course_type'];
                unset($arr_where['course_type']);
            }
            if(array_key_exists('teacher_id',$arr_where)){
                $arr_where[TABLE_ROUND.'.teacher_id'] = $arr_where['teacher_id'];
                unset($arr_where['teacher_id']);
            }
            if(array_key_exists('id',$arr_where)){
                $arr_where[TABLE_ROUND.'.id'] = $arr_where['id'];
                unset($arr_where['id']);
            }
            if(array_key_exists('title',$arr_where)){
                $arr_where['like'][TABLE_ROUND.'.title'] = $arr_where['title'];
                unset($arr_where['title']);
            }
            $arr_group_by = array(
                TABLE_ROUND.'.id'
            );
            $arr_order_by = array(
                TABLE_ROUND.'.create_time' => 'desc'
            );
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_return = $this->model_round->get_round_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, $arr_order_by,$arr_limit);
        }
//        o($arr_return,true);
        return $arr_return;
    }
    /**
     * 根据id取course
     * @param $int_round_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_round_by_id($int_round_id)
    {
        $arr_return = array();
        if($int_round_id){
            $str_table_range = 'round';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_round_id
            );
            $arr_return = $this->model_round->get_round_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * get teachers by round_id
     * @param $int_round_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_teachers_by_round_id($int_round_id){
        $arr_return = array();
        if($int_round_id > 0){
            $str_table_range = 'round_teachers';
            $str_result_type = 'list';
            $str_fields = TABLE_USER.'.id,nickname';
            $arr_where = array(
                'round_id' => $int_round_id
            );
            $arr_return = $this->model_round->get_round_teacher_relation_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

}