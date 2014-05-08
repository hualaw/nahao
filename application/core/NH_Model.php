<?php
class NH_Model extends CI_Model
{
    /**
     * 对象实例计数器
     * @var int
     */
    static $obj_count = 0;
    /**
     * @var 判断get_magic_quotes_runtime
     */
    public $boolMagic;

    public function __construct()
    {
        $this->load->database();
        ++self::$obj_count;
        parent::__construct();
        $this->boolMagic = get_magic_quotes_runtime();
    }
    
    /**
     * 获取一条数据
     * @param string $str_table
     * @param string $str_field
     * @param array $arr_were
     * @param string $str_order_by  // 'id asc'      'id DESC'      'rand()'随机
     * @return array
     * @author liangkun 2014-4-9
     */
    public function _get_one ( $str_table = '', $str_field='*', $arr_were=array(), $str_order_by='') {
    	$arr_result = array();
    	if ($str_table && $arr_were) {
    		if ($str_order_by) {
    			$this->db->order_by($str_order_by);
    		}
    		$arr_result = $this->db->select($str_field)->from($str_table)->where($arr_were)->get()->row_array();
    	}
    	return $arr_result;
    }
    
    /**
     * 获取全部数据
     * @param string $str_table
     * @param string $str_field
     * @param array $arr_were
     * @param string $str_order_by  // 'id asc'      'id DESC'      'rand()'随机
     * @return array
     * @author liangkun 2014-4-9
     */
    public  function _get_all ( $str_table = '', $str_field='*', $arr_were=array(), $str_order_by='') {
    	$arr_result = array();
    	if ($str_table && $arr_were) {
    		if ($str_order_by) {
    			$this->db->order_by($str_order_by);
    		}
    		$arr_result = $this->db->select($str_field)->from($str_table)->where($arr_were)->get()->result_array();
    	}
    	return $arr_result;
    }
   /**
    * 修改单条数据
    * @param string $str_table
    * @param array $arr_data
    * @param array $arr_where
    * @return int
    * @author liangkun 2014-4-4
    */
    public function _update_one( $str_table = '', $arr_data=array(), $arr_where=array()) {
    	
    	$int_affected_rows = 0;
    	if ($str_table && $arr_where){
    		$str_sql  = $this->db->update_string($str_table, $arr_data, $arr_where);
    		$str_sql .= ' LIMIT 1';
    		$this->db->query($str_sql);
    		$int_affected_rows = $this->db->affected_rows();
    	}
    	return $int_affected_rows;
 
    }
}