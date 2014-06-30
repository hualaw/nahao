<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 15分钟定时统计
 */
class Crontab extends NH_Controller
{
    /**
     * 加载数据库连接
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('business/auto/business_crontab');
    }

    /**
     * 到课开始时间前30分钟 将课的状态从“即将上课”改为“可进教室”
     */
    private function Class_Change_SoonClass_To_ClassEnterable()
    {

    }

    /**
     *轮的销售状态 每15分钟一个周期
     * @author shangshikai@tizi.com
     */
    public function change_round_status()
    {
        //echo "fs";die;
        $this->business_crontab->round_change_status();
    }
}