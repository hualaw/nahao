<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 那好Model超级父类
 * @author yanrui@91waijiao.com
 */
class NH_Model extends CI_Model
{
    /**
     * @var 判断get_magic_quotes_runtime
     */
    public $boolMagic;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->boolMagic = get_magic_quotes_runtime();
    }

    /**
     * 读取配置拿到表，去数据库中查询
     * @param string $str_table_range
     * @param string $str_result_type
     * @param string $str_field
     * @param array $arr_where
     * @param array $arr_group_by
     * @param array $arr_order_by
     * @param array $arr_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function _get_from_db($str_table_range = 'admin', $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = array();
//        echo $str_table_range.'--'.$str_result_type.'--'.$str_field."\n";echo "where : \n";var_dump($arr_where);echo "group_by : \n";var_dump($arr_group_by);echo "order_by : \n";var_dump($arr_order_by);echo "limit : \n";var_dump($arr_limit);//exit;
        if (is_array($arr_where)) {
            $arr_config = config_item('sql_'.DOMAIN);
            if(array_key_exists($str_table_range,$arr_config)){
                $arr_keys = array_keys($arr_config[$str_table_range]);
                $arr_keys = array_flip($arr_keys);
                foreach($arr_config[$str_table_range] as $k => $v){
                    if($arr_keys[$k]==0){
                        $this->db->from($k);
                    }else{
                        $this->db->join($k, $v[0],$v[1]);
                    }
                }
                $this->db->select($str_field);
                if (!empty($arr_where)) {
                    foreach ($arr_where as $k => $v) {
                        if (is_array($v)) {
                            $this->db->where_in($k, $v);
                        } else {
                            $this->db->where($k, $v);
                        }
                    }
                }
                if (!empty($arr_group_by)) {
                    foreach ($arr_group_by as $value) {
                        $this->db->group_by($value);
                    }
                }
                if (!empty($arr_order_by)) {
                    foreach ($arr_order_by as $k => $v) {
                        $str_order = $v ? $v : 'DESC';
                        $this->db->order_by($k, $str_order);
                    }
                }
                if (!empty($arr_limit)) {
                    $this->db->limit($arr_limit['limit'], $arr_limit['start']);
                }
                if ($str_result_type == 'count') {
                    $mix_return = $this->db->count_all_results();
                } elseif ($str_result_type == 'one') {
                    $mix_return = $this->db->get()->row_array();
                } elseif ($str_result_type == 'list') {
                    $mix_return = $this->db->get()->result_array();
                }
            }else{
                die('no such table range : '.$str_table_range);
            }

//                var_dump($this->db->last_query());//exit;
        }
        return $mix_return;
    }
}