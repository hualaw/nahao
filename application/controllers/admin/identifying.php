<?php
    class Identifying extends NH_Admin_Controller
    {
        /**
         * 查询验证码
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $this->smarty->assign('view','identifying');
            $this->smarty->display('admin/layout.html');
        }

        public function dentifying_code()
        {
            $phone=trim($this->input->post('phone',TRUE));
            if(!is_mobile($phone))
            {
                redirect('/identifying');
            }
            $arr_captcha=$this->identifying->code_dentifying($phone);
            $this->smarty->assign('arr_captcha',$arr_captcha);
            $this->smarty->assign('phone',$phone);
            $this->smarty->assign('view','show_phone');
            $this->smarty->display('admin/layout.html');
        }

        public function eny_phone()
        {
            $phone=trim($this->input->post('phone',TRUE));
            if(!is_mobile($phone))
            {
                echo 0;
            }
            else
            {
                echo 1;
            }
        }
    }