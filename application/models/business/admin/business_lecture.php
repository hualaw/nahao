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

//        /**
//         *待定试讲审核
//         * @param
//         * @return
//         * @author shangshikai@nahao.com
//         */
//        public function lecture_indeterminate($lecture_id)
//        {
//            $this->load->model('model/admin/model_lecture');
//            return $this->model_lecture->lecture_teach_indeterminate($lecture_id);
//        }
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

        /**
         * 不允许试讲
         * @author shangshikai@tizi.com
         */
        public function lecture_disagree($lecture_id)
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->lecture_teach_disagree($lecture_id);
        }
        /**
         * 允许试讲
         * @author shangshikai@tizi.com
         */
        public function lecture_agree($lecture_id,$start_time,$end_time,$subject,$course,$int_classroom_id,$user_id)
        {
            $this->load->model('model/admin/model_lecture');
            $data=array(
                TABLE_LECTURE_CLASS.'.begin_time'=>$start_time,
                TABLE_LECTURE_CLASS.'.end_time'=>$end_time,
                TABLE_LECTURE_CLASS.'.title'=>$course,
                TABLE_LECTURE_CLASS.'.status'=>2,
                TABLE_LECTURE_CLASS.'.subject'=>$subject,
                TABLE_LECTURE_CLASS.'.round_id'=>1000,
                TABLE_LECTURE_CLASS.'.classroom_id'=>$int_classroom_id,
                TABLE_LECTURE_CLASS.'.user_id'=>$user_id
            );
            $data_calss=array(
                TABLE_CLASS.'.begin_time'=>$start_time,
                TABLE_CLASS.'.end_time'=>$end_time,
                TABLE_CLASS.'.title'=>$course,
                TABLE_CLASS.'.status'=>2,
                TABLE_CLASS.'.round_id'=>1000,
                TABLE_CLASS.'.classroom_id'=>$int_classroom_id,
                TABLE_CLASS.'.is_test'=>2
            );
            return $this->model_lecture->lecture_teach_agree($lecture_id,$data,$data_calss);
        }
        /**
         * 试讲课列表
         * @author shangshikai@tizi.com
         */
        public function list_lecture_class($title)
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->lecture_class($title);
        }
        /**
         * 试讲课列表数
         * @author shangshikai@tizi.com
         */
        public function total_lecture_class($title)
        {
            $this->load->model('model/admin/model_lecture');
            return $this->model_lecture->lecture_class_total($title);
        }
        /**
        * 添加试讲课件
        * @author shangshikai@tizi.com
        */
        public function create_courseware($int_class_id, $arr_courseware,$int_classroom_id){
            $bool_return = false;
            if($int_class_id > 0 AND is_array($arr_courseware)){
                $this->load->model('business/common/business_courseware','courseware');
                $this->courseware->create_courseware($arr_courseware);
                $arr_param = array(
                    'courseware_id' => $arr_courseware['id'],
                );
                $arr_where = array(
                    'id' => $int_class_id
                );
                $arr_class_where=array('classroom_id'=>$int_classroom_id);
                $this->load->model('model/admin/model_lecture');
                $bool_return = $this->model_lecture->update_lecture_class($arr_param, $arr_where,$arr_class_where);
            }
            return $bool_return;
        }


        /**
         * 根据classroom_id取class
         * @param $int_classroom_id
         * @return array
         * @author shangshikai@tizi.com
         */
        public function get_class_by_classroom_id($int_classroom_id){
            $this->load->model('model/admin/model_lecture');
            $arr_return = array();
            if($int_classroom_id){
                $arr_where = array(
                    'classroom_id' => $int_classroom_id
                );
                $arr_return = $this->model_lecture->get_lecture_class($arr_where);
            }
            return $arr_return;
        }

}