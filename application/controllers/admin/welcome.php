<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends NH_Student_Controller {

    /**
     * @author yanrui@91waijiao.com
     */

    public function index()
    {
        redirect('/passport');
    }

    public function main(){
        $data['str'] = 'welcome! layout!';
        $this->layout->set_layout('admin/layout');
        $this->layout->view('www/test',$data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */