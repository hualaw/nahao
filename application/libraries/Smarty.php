<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('Smarty/Smarty.class.php');

class CI_Smarty extends Smarty{

	private $_CI;

    protected static $default_config = array(
        'template_dir' => 'views',
        'compile_dir' => 'templates_c',
        'cache_dir' => 'cache',
        'caching' => true,
        //'cache_lifetime' => 120,
        //'debugging' => false,
        'debugging' => false,
        'compile_check' => false,
        'force_compile' => false,
        //'allow_php_templates' = true,
        'left_delimiter' => '{',
        'right_delimiter' => '}'
    );

    function __construct() {
    	parent::__construct();
                
        $this->_CI = &get_instance();

        $this->_CI->load->library('user_agent');

        $config = array();

        if ($this->_CI->config->load('smarty', true, true))
        {
            $config = $this->_CI->config->item('smarty');
        }

        $config = array_merge(self::$default_config, $config);

        $this->template_dir = $config['template_dir'];
        $this->compile_dir = $config['compile_dir'];
        $this->cache_dir = $config['cache_dir'];
        $this->caching = $config['caching'];
        $this->cache_lifetime = $config['lifetime'];
        $this->debugging = $config['debugging'];
        $this->compile_check = $config['compile_check'];
        $this->force_compile = $config['force_compile'];
        //$this->allow_php_templates= $config['allow_php_templates'];
        $this->left_delimiter = $config['left_delimiter'];
        $this->right_delimiter = $config['right_delimiter'];
	}

    public function isCached($template = null, $cache_id = null, $compile_id = null, $parent = null, $caching = true)
    {
        $this->caching = !$this->force_compile && $caching && $cache_id ? true : false;
        return parent::isCached($template, $cache_id, $compile_id, $parent);
    }

    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null, $caching = true)
    {
        // display template
        $this->fetch($template, $cache_id, $compile_id, $parent, true, $caching);
    }

    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false, $caching = true)
    {
        $this->caching = !$this->force_compile && $caching && $cache_id ? true : false;
        return parent::fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
    }
}
/* End of file Smarty.php */
/* Location: ./application/libraries/Smarty.php */
