<?php
class Business_employment extends NH_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model/student/model_employment');
        $arr_return = array();
    }

    /**
     * @return mixed
     * 招聘信息总数
     * @author shangshikai@tizi.com
     */
    public function num_employment()
    {
        return $this->model_employment->count_employment();
    }

    /**
     * @return mixed
     * 获取招聘信息
     * @author shanghsikai@tizi.com
     */
    public function get_employment($id)
    {
        return $this->model_employment->getAll_employment($id);
    }

    /**
     * @param $arr_insert
     * @return mixed
     * 添加招聘信息
     * @author shangshikai@tizi.com
     */
    public function add_employment($arr_insert)
    {
        $arr_insert['title']=trim($arr_insert['title']);
        $arr_insert['desc']=trim($arr_insert['desc']);
        $arr_insert['requirement']=trim($arr_insert['requirement']);
        $arr_insert['is_open']=1;
        $arr_insert['seq']=trim($arr_insert['seq']);

        if($arr_insert['title']=='' || $arr_insert['desc']=='' || $arr_insert['requirement']=='' || $arr_insert['seq']=='')
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'信息填写不完整',
            );

            return $arr_return;
        }

        if(!is_numeric($arr_insert['seq']))
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'顺序不是一个数字',
            );

            return $arr_return;
        }

        if($this->model_employment->unique_seq($arr_insert['seq']))
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'顺序已被占用',
            );

            return $arr_return;
        }

        $int_id=$this->model_employment->insert_employment($arr_insert);
        if($int_id>0)
        {
            $arr_return=array(
                'status'=>'ok',
                'msg'=>'添加成功',
                'redirect'=>'/employment'
            );

            return $arr_return;
        }
        else
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'添加失败',
            );

            return $arr_return;
        }
    }

    /**
     * @param $arr_edit
     * @param $innate
     * @return array
     * 修改招聘信息
     * @author shangshikai@tizi.com
     */
    public function modify_employment($arr_edit)
    {
        $arr_modify['title']=trim($arr_edit['title']);
        $arr_modify['desc']=trim($arr_edit['desc']);
        $arr_modify['requirement']=trim($arr_edit['requirement']);
        $arr_modify['seq']=trim($arr_edit['seq']);
        if($arr_edit['title']=='' || $arr_edit['desc']=='' || $arr_edit['requirement']=='' || $arr_edit['seq']=='')
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'信息填写不完整',
            );

            return $arr_return;
        }
        if(!is_numeric($arr_edit['seq']))
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'顺序不是一个数字',
            );

            return $arr_return;
        }
        if($this->model_employment->unique_seq($arr_modify['seq'],trim($arr_edit['innate'])))
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'顺序已被占用',
            );

            return $arr_return;
        }
        $arr_where=array(TABLE_EMPLOYMENT.'.id'=>trim($arr_edit['id']));
        $int_id=$this->model_employment->update_employment($arr_modify,$arr_where);
        if($int_id>0)
        {
            $arr_return=array(
                'status'=>'ok',
                'msg'=>'修改成功',
                'redirect'=>'/employment',
            );

            return $arr_return;
        }
        else
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'修改失败',
            );

            return $arr_return;
        }
    }

    /**
     * @param $is_open
     * @param $id
     * @return array
     * 招聘信息启用/禁用
     * @author shangshikai@tizi.com
     */
    public function open_close($is_open,$id)
    {
        if($is_open==1)
        {
            $arr_edit=array(TABLE_EMPLOYMENT.'.is_open'=>0);
        }

        if($is_open==0)
        {
            $arr_edit=array(TABLE_EMPLOYMENT.'.is_open'=>1);
        }
        $arr_where=array(TABLE_EMPLOYMENT.'.id'=>$id);
        $int_id=$this->model_employment->go_employment($arr_edit,$arr_where);
        if($int_id>0)
        {
            $arr_return=array(
                'status'=>'ok',
                'msg'=>'修改成功',
            );

            return $arr_return;
        }
        else
        {
            $arr_return=array(
                'status'=>'error',
                'msg'=>'修改失败',
            );

            return $arr_return;
        }
    }
}