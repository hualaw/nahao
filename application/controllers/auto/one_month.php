<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 月底定时
 */
class one_month extends NH_Controller
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
     * 教师课酬统计
     */
    private function teacher_checkout()
    {
        
    }
}