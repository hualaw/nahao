<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class NH_Admin_Controller
 * @author yanrui@tizi.com
 */
class NH_Crm_Controller extends NH_Controller
{
    protected $arr_admin_init_css = array(
        STATIC_ADMIN_CSS_PUBLIC
    );
    protected $arr_admin_init_js = array(
        STATIC_ADMIN_JS_SEA,
        STATIC_ADMIN_JS_CONFIG
    );
    protected $arr_static = array();

    function __construct()
    {
        parent::__construct();
        $this->arr_admin_init_css = array(STATIC_ADMIN_CSS_PUBLIC);
        $this->smarty->assign('js_module', DOMAIN.ucfirst($this->current['controller']));
        
        foreach($this->arr_admin_init_css as $k => $v){
            $this->arr_static['css'][] = '<link href="'.static_url($v).'" rel="stylesheet">';
        }
        foreach($this->arr_admin_init_js as $k => $v){
            $this->arr_static['js'][] = '<script type="text/javascript" src="'.static_url($v).'"></script>';
        }
        $this->smarty->assign('static',$this->arr_static);
    }

}



