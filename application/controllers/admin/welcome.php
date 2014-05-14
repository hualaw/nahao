<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends NH_Student_Controller {

    /**
     * @author yanrui@91waijiao.com
     */

    public function index()
    {
//        var_dump($_SERVER);exit;
        redirect('/passport');
    }

    public function main(){
        $data['str'] = 'welcome! layout!';
        $this->layout->set_layout('admin/layout');
        $this->layout->view('admin/main',$data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */