<?php

class login extends NH_Controller
{

	public function index()
	{
		$this->smarty->display('www/login/login.html');
	}
}
