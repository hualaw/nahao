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

    public function getAll()
    {
        $sql = "select `title`, `desc`, `requirement` from " . TABLE_EMPLOYMENT . " where `is_open` = 1 order by `seq` desc";
        return $this->db->query($sql)->result_array();
    }

    public function update()
    {
    }

    public function insert()
    {
    }

    public function delete()
    {
    }
}
