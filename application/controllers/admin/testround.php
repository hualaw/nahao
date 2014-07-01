<?php
    class Testround extends NH_Admin_Controller {

        public function index()
        {
            $this->smarty->assign('view', 'testround');
            $this->smarty->display('admin/layout.html');
        }

        public function edit()
        {
            $post=$this->input->post(NULL,TRUE);
            echo $this->testround->modify($post);
        }
    }
