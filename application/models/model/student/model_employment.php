<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 获取招聘信息
 * Class Model_Student_Subject
 * @author liuhua@tizi.com
 */
class Model_employment extends NH_Model{

    function __construct(){
        parent::__construct();
    }

    /**
     * @return mixed
     * 查询所有招聘信息
     * @author shangshikai@tizi.com
     */
    public function getAll_employment()
    {
        self::sql();
        $arr_list=$this->db->get()->result_array();
        return $arr_list;
    }
    /**
     * @return array
     * 查询启用的招聘信息
     * @author shangshikai@tizi.com
     */
    public function getAll()
    {
        self::sql();
        $this->db->where(TABLE_EMPLOYMENT.'.is_open',1)->order_by(TABLE_EMPLOYMENT.'.seq','desc');
        $arr_list=$this->db->get()->result_array();
        return $arr_list;
    }

    /**
     * @return mixed
     * 统计招聘信息总数
     * @author shangshikai@tizi.com
     */
    public function count_employment()
    {
        self::sql();
        $int_num=$this->db->get()->num_rows();
        return $int_num;
    }
    /**
     * 拼装sql查询语句
     * @author shangshikai@tizi.com
     */
    public function sql()
    {
        $this->db->select(TABLE_EMPLOYMENT.'.`id`,`title`,`desc`,`requirement`,`is_open`,`seq`')->from(TABLE_EMPLOYMENT);
    }

    /**
     * @param int $arr_update
     * @param $arr_where
     * @return bool
     * 更新招聘信息
     * @author sahngshikai@tizi.com
     */
    public function update_employment($arr_update,$arr_where)
    {
        $this->db->update(TABLE_EMPLOYMENT,$arr_update,$arr_where);
        $int_return=$this->db->affected_rows();
        return $int_return;
    }

    /**
     * @param $arr_insert
     * @return mixed
     * 添加招聘信息
     * @author shangshikai@tizi.com
     */
    public function insert_employment($arr_insert)
    {
        $this->db->insert(TABLE_EMPLOYMENT,$arr_insert);
        $int_return=$this->db->insert_id();
        return $int_return;
    }

    /**
     * @param $int_seq
     * @return mixed
     * 招聘顺序信息的唯一性验证
     * @author shangshikai@tizi.com
     */
    public function unique_seq($int_seq,$innate=0)
    {
        self::sql();
        $this->db->where(TABLE_EMPLOYMENT.'.seq',$int_seq);
        $this->db->where(TABLE_EMPLOYMENT.'.seq!=',$innate);
        $this->db->get()->row_array();
        $int_return=$this->db->affected_rows();
        return $int_return;
    }
}
