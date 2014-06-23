<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Crontab
 * @author yanrui@tizi.com
 */
class Crontab extends NH_Controller {

    /**
     *    轮授课状态
    1等待开课
    2授课中
    3停课（手动操作）、
    4结课
    5过期(节课后一个月cron会把这个状态改为过期)
     */
    /**
     *    轮销售状态
    0 未审核、
    1 审核不通过、
    2 审核通过（预售）、
    3 销售中、
    4 已售罄、
    5 已停售（时间到了还没售罄）、
    6 已下架（手动下架）
     */
    public function change_class_status(){

    }

    public function change_round_status(){

    }

    public function teacher_checkout(){

    }

    public function finish_class(){

    }

    public function class_status(){
        //init2comingsoon
    }
}