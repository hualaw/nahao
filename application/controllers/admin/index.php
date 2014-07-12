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

    public function index()
    {
        $this->smarty->assign('view', 'index_main');
        $this->smarty->display('admin/layout.html');
    }
}