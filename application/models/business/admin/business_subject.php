<?php
    class Business_subject extends NH_Model
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/admin/model_subject');
        }
        /**
         * 获取全部学科
         * @author shangshikai@tizi.com
         */
        public function all_subject($status,$name)
        {
            $name=trim($name);
            return $this->model_subject->subject_all($status,$name);
        }
        /**
         * 学科数量
         * @author shangshikai@tizi.com
         */
        public function total_subject($status,$name)
        {
            $name=trim($name);
            return $this->model_subject->subject_total($status,$name);
        }
        /**
         * 禁用学科
         * @author shangshikai@tizi.com
         */
        public function subject_close($ids)
        {
            $ids=explode(',',$ids);
            return $this->model_subject->disabled_subject($ids);
        }
        /**
         * 启用学科
         * @author shangshikai@tizi.com
         */
        public function subject_open($ids)
        {
            $ids=explode(',',$ids);
            return $this->model_subject->open_subject($ids);
        }
        /**
         * 添加学科
         * @author shangshikai@tizi.com
         */
        public function insert_subject($name,$id)
        {
            $name=trim($name);
            if($name=="")
            {
                return FALSE;
            }
            if(self::subject_only($name)==FALSE)
            {
                return FALSE;
            }
            else
            {
                if($id==-1)
                {
                    $data=array(
                        TABLE_SUBJECT.'.name'=>$name,
                        TABLE_SUBJECT.'.status'=>1
                    );
                    return $this->model_subject->add_subject($data);
                }
                else
                {
                    $data=array(
                        TABLE_SUBJECT.'.name'=>$name,
                    );
                    return $this->model_subject->edit_subject($data,$id);
                }
            }
        }
        /**
         * 学科是否存在
         * @author shangshikai@tizi.com
         */
        public function subject_only($name)
        {
            return $this->model_subject->only_subject($name);
        }
        /**
         * 删除学科
         * @author shangshikai@tizi.com
         */
        public function subject_del($ids)
        {
            $ids=explode(',',$ids);
            return $this->model_subject->delete_subject($ids);
        }
    }