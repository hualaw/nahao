<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * 学生课堂活动记录，包括赞，讲快一点，讲慢一点
 * Class Model_Class_Action_Log
 * 使用方法：
 * $this->load->model('model/student/model_class_action_log', cal_obj);
 * $this->cal_obj->set_class_id($class_id);
 *
 * @author liuhua@tizi.com
 */
class Model_Class_Action_Log extends NH_Model {

    public function __construct()
    {
        parent::__construct();
    }


    public function save_action($classroom_id, $user_id, $user_type, $action_type)
    {
        $record = array(
            'user_id' => $user_id,
            'user_type'=>$user_type,
            'classroom_id' => $classroom_id,
            'action' => $action_type,
            'create_time' => time(),
        );

        $this->db->insert(TABLE_CLASS_ACTION_LOG, $record);
        return $this->db->affected_rows();
    }

    public function get_action_stat($classroom_id)
    {
        $sql = "select `action`, count(*) as count from ".TABLE_CLASS_ACTION_LOG." where `classroom_id` = ? group by `action`";
        $result = $this->db->query($sql, array($classroom_id))->result_array();

        return $result;
    }
} 