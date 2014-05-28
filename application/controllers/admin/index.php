<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Welcome
 * @author yanrui@tizi.com
 */
class Index extends NH_Admin_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 去判断登录状态
     * @author yanrui@tizi.com
     */
    public function index()
    {
        redirect('/passport');
    }

    /**
     * admin首页
     * @author yanrui@tizi.com
     */
    public function main()
    {
        $data['str'] = 'welcome! layout!';
//        o($this->userinfo);
        $this->smarty->assign('view', 'index_main');
        $this->smarty->display('admin/layout.html');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */