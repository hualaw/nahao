<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Index相关逻辑
 * Class Business_Index
 * @author yanrui@tizi.com
 */
class Business_Index extends NH_Model{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model/admin/model_index');
    }
    /**
     * 预估某个时间段的总上课人数
     * @author shangshikai@tizi.com
     */
    public function goclass_num($str_time)
    {
        $arr_time=array();
        $arr_list=array();
        $str_start_day=date('d',$str_time);
        $str_start_mon=date('m',$str_time);
        $str_start_year=date('Y',$str_time);
        for($i=0;$i<=23;$i++)
        {
            array_push($arr_time,mktime($i,0,0,$str_start_mon,$str_start_day,$str_start_year));
        }
        foreach($arr_time as $k=>$v)
        {
            if($k!=23)
            {
                $str_start_time=$arr_time[$k];
                $str_end_time=$arr_time[$k+1];
                $arr_list[]=$this->model_index->num_goclass($str_start_time,$str_end_time);
            }
            else
            {
                $str_start_time=$arr_time[$k];
                $tomorrow=mktime(23,59,59,$str_start_mon,$str_start_day,$str_start_year);
                $arr_list[]=$this->model_index->num_goclass($str_start_time,$tomorrow);
            }
        }
//        var_dump($arr_list);die;
        foreach($arr_list as $k=>$v)
        {
            $arr_list[$k]['total']=0;
            $arr_list[$k]['count']=0;
            foreach($v as $vv)
            {
                $arr_list[$k]['total']+=$vv['caps'];
                $arr_list[$k]['count']+=$vv['con'];
            }
        }
        return $arr_list;
    }
}