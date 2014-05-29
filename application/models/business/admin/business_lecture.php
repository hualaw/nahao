<?php
    class business_lecture extends NH_Model
    {
        /**
         * 查询试讲列表
         * @param
         * @return array
         * @author shangshikai@nahao.com
         */
        public function lecture_list()
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->lecture_data();
        }

        /**
         * 查询试讲记录数
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public function lecture_total()
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->total_lecture();
        }
        /**
         * 查询搜索试讲记录数
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public function sea_lecture($get)
        {
            $get['basis']=trim($get['basis']);
            $get['create_time1']=strtotime($get['create_time1']);
            $get['create_time2']=strtotime($get['create_time2']);
            $this->load->model('model/admin/model_lecture');
            //var_dump($get);die;
            return $this->model_lecture->total_sea($get);
        }
        /**
         * 查询复合搜索条件的记录
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public function lecture_seach_list($get)
        {
            $get['basis']=trim($get['basis']);
            $get['create_time1']=strtotime($get['create_time1']);
            $get['create_time2']=strtotime($get['create_time2']);
            $this->load->model('model/admin/model_lecture');
            //var_dump($get);die;
            return $this->model_lecture->seach_lecture_list($get);
        }
        /**
         * 当天申请试讲的人数
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public  function day_lecture()
        {
            $day=date('Y-m-d',time());
            $day_start=strtotime($day." 00:00:00");
            $day_end=strtotime($day." 23:59:59");
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->lecture_day($day_start,$day_end);
        }
     /**
     * 查询全部省份
     * @param
     * @return int
     * @author shangshikai@nahao.com
     */
        public function all_province()
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->province_all();
        }
        /**
         * 试讲详情
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function details_lecture($lecture_id)
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->detail_lecture($lecture_id);
        }
        /**
         * 查询试讲备注
         * @param
         * @return
         * @author shangshikai@nahao.com
        */
        public function notes($lecture_id)
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->note_lecture($lecture_id);
        }
        /**
         * 添加试讲备注和修改审核状态
         * @param
         * @return
         * @author shangshikai@nahao.com
        */
        public function note_insert($post)
        {
            $post['lectrue_notes']=htmlspecialchars($post['lectrue_notes']);
            if(trim($post['lectrue_notes'])=="")
            {
                return FALSE;
            }
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->insert_notes($post);
        }
    }