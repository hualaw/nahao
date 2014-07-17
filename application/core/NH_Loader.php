<?php
class NH_Loader extends CI_Loader
{

    private static $_models = array();

    public function table($table_name, $name = '', $return = false)
    {
        $name = ($name === '' ? $table_name : $name);
        $CI =& get_instance();
        if (in_array($name, $this->_ci_models, TRUE)) {
            return $return === true ? $CI->$name : null;
        }

        if (!class_exists('CI_Model')) {
            load_class('Model', 'core');
        }
        $CI->$name = new NH_Model($table_name);
        return $return === true ? $CI->$name : null;
    }

}

if (!function_exists('T')) {
    function T($table_name)
    {
        $CI =& get_instance();
        return $CI->load->table($table_name, "tab_{$table_name}", true);
    }
}


function dbcache_on()
{
    $CI =& get_instance();
    if (! isset($CI->db)) {
        $CI->load->database();
    }
    $CI->db->cache_on();
    return ;
}

function dbcache_off()
{
    $CI =& get_instance();
    if (! isset($CI->db)) {
        $CI->load->database();
    }
    $CI->db->cache_off();
    return ;
}