<?php
    class Model_Lecture extends NH_Model
    {
        /**
         * 查询试讲列表
         * @param
         * @return array
         * @author shangshikai@nahao.com
         */
        public function lecture_data()
        {
            $this->load->model('model/admin/model_lecture');
            $this->model_lecture->sql();
            return $this->db->get();
        }

        /**
         *
         * @param 查询的公共sql部分
         * @return
         * @author shangshikai@nahao.com
         */
        public function sql()
        {
            $this->db->select('teacher_lecture.course,teacher_lecture.id,teacher_lecture.start_time,teacher_lecture.phone,teacher_lecture.title,teacher_lecture.teach_years,teacher_lecture.subject,teacher_lecture.teach_type,teacher_lecture.school,teacher_lecture.create_time,teacher_lecture.status,teacher_lecture.name as tea_name,teacher_lecture.stage,nahao_schools.schoolname,nahao_areas.name,subject.name as sub_name')->from('teacher_lecture')->join('nahao_schools','nahao_schools.id=teacher_lecture.school','left')->join('nahao_areas','nahao_areas.id=teacher_lecture.province','left')->join('subject','subject.id=teacher_lecture.subject','left')->order_by('teacher_lecture.id','desc');
        }

        /**
         * 查询试讲记录数
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public function total_lecture()
        {
            return $this->db->get('teacher_lecture')->num_rows();
        }
        /**
         * 搜索试讲的记录数
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public function total_sea($get)
        {
            if($get['subject']!=0)
            {
                $this->load->model('business/common/business_subject');
                $lecture_subject=$this->business_subject->get_subject_by_id($get['subject']);
            }
            else
            {
                $lecture_subject['name']='';
            }
            $this->load->model('model/admin/model_lecture');
            $this->model_lecture->factor($get,$lecture_subject['name']);
            return $this->db->get()->num_rows();
         //   echo $this->db->last_query();die;
        }
        /**
         * 符合搜索条件的纪录
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function seach_lecture_list($get)
        {
            $this->load->model('model/admin/model_lecture');
            $this->model_lecture->factor($get);
            return $this->db->get();
        }
        /**
         * 拼装搜索条件
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public function factor($get)
        {
            $config_lecture = config_item('lecture_factor');
            if($get['term']!=0)
            {
                $con_lec=$config_lecture[$get['term']];
            }
            //var_dump($con_lec);die;
            $this->load->model('model/admin/model_lecture');
            $this->model_lecture->sql();
            if($get['term']!=0)
            {
                if($get['term']!=3)
                {
                    $this->db->where("teacher_lecture.$con_lec='$get[basis]'");
                }
                else
                {
                    $this->db->where("teacher_lecture.$con_lec=$get[basis]");
                }
            }
            if($get['subject']!=0)
            {
                $this->db->where("teacher_lecture.subject=$get[subject]");
            }
            if($get['teach_type']!=0)
            {
                $this->db->where("teacher_lecture.teach_type=$get[teach_type]");
            }
            if($get['province']!=0)
            {
                $this->db->where("teacher_lecture.province=$get[province]");
            }
            if($get['status']!=0)
            {
                $this->db->where("teacher_lecture.status=$get[status]");
            }
            if($get['create_time1']!="" && $get['create_time2']!="")
            {
                $this->db->where("teacher_lecture.create_time >",$get['create_time1']);
                $this->db->where("teacher_lecture.create_time <",$get['create_time2']);
            }
        }
        /**
         * 当天申请试讲的人数
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public  function lecture_day($day_start,$day_end)
        {
           // echo $day_start,'<br />',$day_end;die;
            $this->db->select('teacher_lecture.id')->from('teacher_lecture');
            $this->db->where('teacher_lecture.create_time >=',$day_start);
            $this->db->where('teacher_lecture.create_time <=',$day_end);
            return $this->db->get()->num_rows();
        }

        /**
         * 查询全部省份
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public function province_all()
        {
            return $this->db->select('nahao_areas.name,nahao_areas.id')->from('nahao_areas')->where('nahao_areas.parentid=1')->get()->result_array();
        }
        /**
         * 试讲详情
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function detail_lecture($lecture_id)
        {
            return $this->db->select('teacher_lecture.province,teacher_lecture.city,teacher_lecture.area,teacher_lecture.course,teacher_lecture.user_id,teacher_lecture.status,teacher_lecture.id,teacher_lecture.start_time,teacher_lecture.end_time,teacher_lecture.phone,teacher_lecture.qq,teacher_lecture.title,teacher_lecture.teach_years,teacher_lecture.subject,teacher_lecture.teach_type,teacher_lecture.school,teacher_lecture.name as tea_name,teacher_lecture.stage,nahao_schools.schoolname,nahao_areas.name,teacher_lecture.resume,teacher_lecture.age,teacher_lecture.course_intro,teacher_lecture.gender,teacher_lecture.email')->from('teacher_lecture')->join('nahao_schools','nahao_schools.id=teacher_lecture.school','left')->join('nahao_areas','nahao_areas.id=teacher_lecture.province','left')->where("teacher_lecture.id=$lecture_id")->get()->row_array();
        }

        /**
         * 查询试讲备注
         * @param
         * @return
         * @author shangshikai@nahao.com
        */
        public function note_lecture($lecture_id)
        {
            return $this->db->select('teacher_lecture_note.note,teacher_lecture_note.create_time,admin.username')->from('teacher_lecture_note')->join('admin','admin.id=teacher_lecture_note.admin_id','left')->where("teacher_lecture_note.lecture_id=$lecture_id")->order_by('teacher_lecture_note.id','desc')->get()->result_array();
            //echo $this->db->last_query();die;
        }

        /**
         * 添加试讲备注
         * @param
         * @return
         * @author shangshikai@nahao.com
        */
        public function insert_notes($post)
        {
            $admin_id=$this->userinfo['id'];
            $data=array("lecture_id"=>$post['lecture_id'],
                "note"=>$post['lectrue_notes'],
                "admin_id"=>$admin_id,
                "create_time"=>time()
            );
            if($this->db->insert('teacher_lecture_note',$data))
            {
                return TRUE;
            }
        }
        /**
         * 试讲老师所在区域
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function area_city($lecture_id)
        {
            $data=array();
            $data['city']=$this->db->select('nahao_areas.name')->from('teacher_lecture')->join('nahao_areas','nahao_areas.id=teacher_lecture.city','left')->where("teacher_lecture.id=$lecture_id")->get()->row_array();
            $data['area']=$this->db->select('nahao_areas.name')->from('teacher_lecture')->join('nahao_areas','nahao_areas.id=teacher_lecture.area','left')->where("teacher_lecture.id=$lecture_id")->get()->row_array();
            return $data;
        }

        /**
         *通过试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function lecture_teach_pass($post,$data,$data_user,$subject_data)
        {
            if($this->db->update('teacher_lecture',array('teacher_lecture.status'=>5),array('teacher_lecture.id'=>$post['lecture_id'])) && $this->db->update('user',$data_user,array('user.id'=>$post['user_id'])) && $this->db->update('user_info',$data,array('user_info.user_id'=>$post['user_id'])) && $this->db->insert('teacher_subject',$subject_data))
            {
                $now_time=time();
                $session_id=$this->db->select('session_log.session_id')->from('session_log')->where(array('session_log.user_id'=>$post['user_id'],'session_log.expire_time>'=>$now_time))->order_by('session_log.generate_time','desc')->limit(1)->get()->row_array();
                $this->load->model('model/common/model_redis', 'redis');
                $this->redis->connect('session');
                $redis_data=$this->cache->redis->get($session_id['session_id']);
                $arr_data=json_decode($redis_data,TRUE);
                $arr_data['user_data']=unserialize($arr_data['user_data']);
                $arr_data['user_data']['user_type']=1;
                $arr_data['user_data']=serialize($arr_data['user_data']);
                $arr_data=json_encode($arr_data);
                if($this->cache->redis->set($session_id['session_id'],$arr_data))
                {
                    return TRUE;
                }
            }
        }
//        /**
//         *待定试讲审核
//         * @param
//         * @return
//         * @author shangshikai@nahao.com
//         */
//        public function lecture_teach_indeterminate($lecture_id)
//        {
//            return $this->db->update('teacher_lecture',array('teacher_lecture.status'=>2),array('teacher_lecture.id'=>$lecture_id));
//        }
        /**
         *不通过试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function lecture_teach_nopass($lecture_id)
        {
            return $this->db->update('teacher_lecture',array('teacher_lecture.status'=>4),array('teacher_lecture.id'=>$lecture_id));
        }
        /**
         * 不允许试讲
         * @author shangshikai@tizi.com
         */
        public function lecture_teach_disagree($lecture_id)
        {
            $this->db->update('teacher_lecture',array('teacher_lecture.status'=>3),array('teacher_lecture.id'=>$lecture_id));
            $int_rows=$this->db->affected_rows();
            return $int_rows;
        }
        /**
         * 允许试讲
         * @author shangshikai@tizi.com
         */
        public function lecture_teach_agree($lecture_id,$data)
        {
            $this->db->update('teacher_lecture',array('teacher_lecture.status'=>2),array('teacher_lecture.id'=>$lecture_id));
            $int_rows=$this->db->affected_rows();
            if($int_rows>0)
            {
                $this->db->insert(TABLE_LECTURE_CLASS,$data);
                $int_lecture_class_id=$this->db->insert_id();
            }
            else
            {
                $int_lecture_class_id=0;
            }
            return $int_lecture_class_id;
        }

        /**
         * 试讲课列表
         * @author shangshikai@tizi.com
         */
        public function lecture_class($title)
        {
            self::lecture_class_sql($title);
            return $this->db->order_by(TABLE_LECTURE_CLASS.'.id','desc')->get()->result_array();
//            return $this->db->last_query();
        }
        /**
         * 试讲课列表数
         * @author shangshikai@tizi.com
         */
        public function lecture_class_total($title)
        {
            self::lecture_class_sql($title);
            return $this->db->get()->num_rows();
        }
        /**
         * 试讲课sql
         * @author shangshikai@tizi.com
         */
        public function lecture_class_sql($title)
        {
            $this->db->select(TABLE_LECTURE_CLASS.'.id,title,begin_time,end_time,subject,courseware_id,classroom_id,
'.TABLE_SUBJECT.'.name')->from(TABLE_LECTURE_CLASS)->join(TABLE_SUBJECT,TABLE_SUBJECT.'.id'.'='.TABLE_LECTURE_CLASS.'.subject','left');
            if($title!='')
            {
                $this->db->like(TABLE_LECTURE_CLASS.'.title',$title);
            }
        }
        /**
         * 添加课件
         * @author shangshikai@tizi.com
         */
        public function update_lecture_class($arr_param,$arr_where){
            $this->db->update(TABLE_LECTURE_CLASS, $arr_param, $arr_where);
            $int_affected_rows = $this->db->affected_rows();
    //        o($int_affected_rows);
            return $int_affected_rows > 0 ? true :false;
        }
        /**
         * 管理员进教室
         * @author shangshikai@tizi.com
         */
        public function get_lecture_class($arr_where)
        {
            return $this->db->select(TABLE_LECTURE_CLASS.'.id,title,begin_time,end_time,subject,courseware_id,classroom_id,round_id,status')->from(TABLE_LECTURE_CLASS)->where($arr_where)->get()->row_array();
        }
}