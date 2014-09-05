<?php
    class Business_focus_photo extends NH_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/admin/model_focus_photo');
        }
        /**
         * 展示焦点图
         * @author shangshikai@tizi.com
         */
        public function list_photo($is_show)
        {
            return $this->model_focus_photo->photo_list($is_show);
        }
        /**
         * 总数量
         * @author shangshikai@tizi.com
         */
        public function row_photo()
        {
            return $this->model_focus_photo->total_photo();
        }
        /**
         * 修改焦点图片
         * @author shangshiki@tizi.com
         */
        public function edit($data)
        {
            $id=$data['photo_id'];
            unset($data['photo_id']);
            return $this->model_focus_photo->modify_edit($id,$data);
        }
        /**
         * 启用/屏蔽
         * @author shangshiki@tizi.com
         */
        public function show_is($id,$is_show)
        {
            $data=array();
            if($is_show==0)
            {
                $data=array('is_show'=>1);
            }
            if($is_show==1)
            {
                $data=array('is_show'=>0);
            }
            return $this->model_focus_photo->modify_edit($id,$data);
        }
        /**
         * 验证轮ID是否存在
         * @author shangshikai@tizi.com
         */
        public function check_round($round_id,$is_round)
        {
            return $this->model_focus_photo->round_check($round_id,$is_round);
        }
        /**
         * 添加轮播图
         * @author shangshikai@tizi.com
         */
        public function create_photo($data)
        {
            $arr_data=array();
            $arr_data['round_id']=trim($data['round_id']);
            $arr_data['color']=trim($data['color']);
            $arr_data['img_src']=trim($data['img_src']);
            $arr_data['sort']=trim($data['sort']);
            return $this->model_focus_photo->success_add($arr_data);
        }
        /**
         * 验证轮播图数序是否存在
         * @author shangshikai@tizi.com
         */
        public function check_sort($sort)
        {
            return $this->model_focus_photo->sort_check($sort);
        }
    }