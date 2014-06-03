<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Passport
 * @author yanrui@tizi.com
 */
class Passport extends NH_Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('business/admin/business_passport', 'passport');
    }

    /**
     * signin
     * @author yanrui@tizi.com
     */
    public function index()
    {
        $this->smarty->display('admin/signin.html');
    }

    /**
     * login
     * @author yanrui@tizi.com
     */
    public function login()
    {
        $str_username = $this->input->post('username') ? trim($this->input->post('username')) : '';
        $str_password = $this->input->post('password') ? trim($this->input->post('password')) : '';
        $str_redirect = '/';
        $bool_return = $this->passport->login($str_username, $str_password);
//        o($bool_return);
        if ($bool_return === true) {
            $str_redirect = '/index/main';
        }
        redirect($str_redirect);

    }

    /**
     * logout
     * @author yanrui@tizi.com
     */
    public function logout()
    {
        $this->passport->logout();
        redirect('/');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */