<?php
    class Count_classroom_num extends NH_Admin_Controller
    {
        /**
         * 展示人数统计
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $type=$this->input->get('type',TRUE) ? $this->input->get('type',TRUE) : 1;
            $arr_classroom=$this->count_classroom_num->classroom_num($type);
            $this->smarty->assign('type',$type);
            $this->smarty->assign('arr_classroom',$arr_classroom);
            $this->smarty->assign('view','count_classroom_num');
            $this->smarty->display('admin/layout.html');
        }
    }