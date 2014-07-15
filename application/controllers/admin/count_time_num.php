<?php
    class Count_time_num extends NH_Admin_Controller
    {
        /**
         * 统计时段人数
         * @shangshikai@tizi.com
         */
        public function index()
        {
            $str_now_time=date('Y-m-d H:i:s',time());
            $str_time=$this->input->get('start_time',TRUE) ? $this->input->get('start_time',TRUE) : $str_now_time;
            $str_time=strtotime($str_time);
            $int_to_time=date('Y-m-d',$str_time);
            $arr_list=$this->count_time_num->goclass_num($str_time);
            $this->smarty->assign('int_to_time', $int_to_time);
            $this->smarty->assign('arr_list', $arr_list);
            $this->smarty->assign('view', 'count_time_num');
            $this->smarty->display('admin/layout.html');
        }
    }