<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * 学生课堂活动记录，包括赞，讲快一点，讲慢一点
 * Class Model_Student_Class_Log
 * 使用方法：
 * $this->load->model('model/student/student_class_log', scl_obj);
 * $this->scl_obj->set_class_id($class_id);
 *
 * @author liuhua@tizi.com
 */
class Model_Class_Action_Log extends NH_Model {

    public function __construct()
    {
        parent::__construct();
    }


    public function save_action($class_id, $user_id, $action_type)
    {
        $record = array(
            'student_id' => $user_id,
            'class_id' => $class_id,
            'action' => $action_type,
            'create_time' => time(),
        );

        $this->db->insert(TABLE_CLASS_ACTION_LOG, $record);
        return $this->db->affected_rows();
    }

    public function get_action_stat($class_id)
    {
        $sql = "select `action`, count(*) as count from ".TABLE_CLASS_ACTION_LOG." where `class_id` = ? group by `action`";
        $result = $this->db->query($sql, array($class_id))->result_array();

        /*
        echo $this->db->last_query();
        echo "<br><pre>";

        print_r($result);
        echo "</pre>";
        */

        return $result;
    }
} 