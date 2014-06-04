<?php

class login extends NH_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('business/common/business_login');
    }

	public function index()
	{
		$this->smarty->display('www/login/login.html');
	}

    public function submit()
    {
        $username = trim($this->input->post('username'));
        $password = trim($this->input->post('password'));

        $ret = $this->business_login->submit($username, $password);

        //var_dump($ret);

        $this->json_output($ret);
    }
}
