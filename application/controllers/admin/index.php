<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Welcome
 * @author yanrui@tizi.com
 */
class Index extends NH_Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 预估某个时间段的总上课人数
     * @author shangshikai@tizi.com
     */
    public function index()
    {
        $str_now_time=date('Y-m-d H:i:s',time());
        $str_time=$this->input->get('start_time',TRUE) ? $this->input->get('start_time',TRUE) : $str_now_time;
        $str_time=strtotime($str_time);
        $int_to_time=date('Y-m-d',$str_time);
        $arr_list=$this->index->goclass_num($str_time);
        $this->smarty->assign('int_to_time', $int_to_time);
        $this->smarty->assign('arr_list', $arr_list);
        $this->smarty->assign('view', 'index_main');
        $this->smarty->display('admin/layout.html');
    }
}