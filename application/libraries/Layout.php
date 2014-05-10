<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Layout
 * @author yanrui@91waijiao.com
 */
class Layout
{

    var $obj;
    var $layout;

    function __construct()
    {
        $this->obj =& get_instance();
    }

    public function set_layout($str_layout){
        $this->layout = $str_layout;
    }

    public function view($view, $data = null, $return = false)
    {
        $data['content_for_layout'] = $this->obj->load->view($view, $data, true);
        if ($return) {
            $output = $this->obj->load->view($this->layout, $data, true);
            return $output;
        } else {
            $this->obj->load->view($this->layout, $data, false);
        }
    }

    public function add_js(){

    }

    public function add_css(){

    }
}
