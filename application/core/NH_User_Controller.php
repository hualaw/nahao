<?php
/**
 * Author: liuhua
 * Date: 14-5-23
 * Time: 下午05:23
 */
class NH_User_Controller extends NH_Controller
{
    /**
     * 用户的详细信息
     * @var array 
     */
    protected $_user_detail = array();
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('business/common/business_user');
        $this->_load_user_detail();
        $this->smarty->assign('user_detail', $this->_user_detail);
    }
    
    /**
     * 加载用户的详细信息
     * @author yanhj
     */
    protected function _load_user_detail()
    {
        $user_id = $this->session->userdata('user_id');
        if($user_id) {
            $this->_user_detail = $this->business_user->get_user_detail($user_id);
        }
    }
}
