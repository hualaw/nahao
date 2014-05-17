<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Passport extends NH_Admin_Controller {

    /**
     * @author yanrui@91waijiao.com
     */

    public function index()
    {
        $this->load->view('admin/signin');
    }

    public function test(){
        $data['str'] = 'welcome! layout!';
        $this->load->library('layout');
        $this->layout->set_layout('admin/layout');
        $this->layout->view('admin/main',$data);
    }

    public function login(){
        $arr_post = $this->input->post();
        var_dump($arr_post);
        redirect('/welcome/main');
    }
    public function order()
    {
        if(!empty($_POST))
        {
            $post=$this->input->post(NULL,TRUE);
            $this->load->model("business/admin/business_admin","order");
            $data["order_arr"]=$this->order->order($post);
            $this->load->view("admin/order_list",$data);
        }
        else
        {
            $this->load->model("business/admin/business_admin","order");
            $data["order_arr"]=$this->order->order_list();
            $this->load->view("admin/order_list",$data);
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */