<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 10分钟定时统计
 */
class ten_minute extends NH_Controller
{
    /**
     * 加载数据库连接
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function void_main()
    {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
        	call_user_func(array($this, $method));
        }

    }

    /**
     * 课的平均分
     */
    private function class_sorce_process()
    {
        
    }

    /**
     * 轮的平均分
     */
    private function round_sorce_process()
    {
        
    }

    /**
     * 课程的平均分
     */
    private function course_sorce_process()
    {
        
    }
    
    /**
     * 课的实到人数
     */
    private function class_attendance_process()
    {
        
    }
    
    /**
     * 课的平均对题率
     */
    private function class_correct_rate_process()
    {
        
    }
}