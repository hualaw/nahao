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
        /**
         * 试讲老师所在区域
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function city_area($lecture_id)
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->area_city($lecture_id);
        }
        /**
         *通过试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function lecture_pass($post)
        {
            if(trim($post['basic_reward'])=="" || !is_numeric($post['basic_reward']) || $post['basic_reward']<0 )
            {
                return 2;
            }
            //var_dump($post);die;
            $data=array(
                'basic_reward'=>$post['basic_reward'],
                'gender'=>$post['gender'],
                'realname'=>$post['realname'],
                'age'=>$post['age'],
                'school'=>$post['school'],
                'province'=>$post['province'],
                'city'=>$post['city'],
                'area'=>$post['area'],
                'stage'=>$post['stage'],
                'teacher_age'=>$post['teacher_age'],
                'teacher_intro'=>$post['teacher_intro'],
                'title'=>$post['title'],
                'titile_auth'=>1,
                'teacher_auth'=>1,
                'teacher_time'=>time()
            );

            $subject_data['subject_id']=$post['subject'];
            $subject_data['teacher_id']=$post['user_id'];

            $data_user=array(
                'phone_verified'=>1,
                'email_verified'=>1,
                'teach_priv'=>1,
                'source'=>0
            );
           // return $data['teacher_auth'];
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->lecture_teach_pass($post,$data,$data_user,$subject_data);
        }

        /**
         *待定试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function lecture_indeterminate($lecture_id)
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->lecture_teach_indeterminate($lecture_id);
        }
        /**
         *不通过试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function lecture_nopass($lecture_id)
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->lecture_teach_nopass($lecture_id);
        }
    }