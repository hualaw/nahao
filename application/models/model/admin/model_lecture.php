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
            $this->db->select('teacher_lecture.course,teacher_lecture.id,teacher_lecture.start_time,teacher_lecture.phone,teacher_lecture.title,teacher_lecture.teach_years,teacher_lecture.subject,teacher_lecture.teach_type,teacher_lecture.school,teacher_lecture.create_time,teacher_lecture.status,teacher_lecture.name as tea_name,teacher_lecture.stage,nahao_schools.schoolname,nahao_areas.name')->from('teacher_lecture')->join('nahao_schools','nahao_schools.id=teacher_lecture.school','left')->join('nahao_areas','nahao_areas.id=teacher_lecture.province','left')->order_by('teacher_lecture.id','desc');
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
            $this->load->model('business/common/business_subject');
            $lecture_subject=$this->business_subject->get_subject_by_id($get['subject']);
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
            $this->load->model('business/common/business_subject');
            $lecture_subject=$this->business_subject->get_subject_by_id($get['subject']);
            $this->load->model('model/admin/model_lecture');
            $this->model_lecture->factor($get,$lecture_subject['name']);
            return $this->db->get();
        }
        /**
         * 拼装搜索条件
         * @param
         * @return int
         * @author shangshikai@nahao.com
         */
        public function factor($get,$subject)
        {
            $config_lecture = config_item('lecture_factor');
            $con_lec=$config_lecture[$get['term']];
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
                $this->db->where("teacher_lecture.subject='$subject'");
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
            return $this->db->select('teacher_lecture.province,teacher_lecture.city,teacher_lecture.area,teacher_lecture.course,teacher_lecture.user_id,teacher_lecture.status,teacher_lecture.id,teacher_lecture.start_time,teacher_lecture.phone,teacher_lecture.qq,teacher_lecture.title,teacher_lecture.teach_years,teacher_lecture.subject,teacher_lecture.teach_type,teacher_lecture.school,teacher_lecture.name as tea_name,teacher_lecture.stage,nahao_schools.schoolname,nahao_areas.name,teacher_lecture.resume,teacher_lecture.age,teacher_lecture.course_intro,teacher_lecture.gender,teacher_lecture.email')->from('teacher_lecture')->join('nahao_schools','nahao_schools.id=teacher_lecture.school','left')->join('nahao_areas','nahao_areas.id=teacher_lecture.province','left')->where("teacher_lecture.id=$lecture_id")->get()->row_array();
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
         * 添加试讲备注和修改审核状态
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
        public function lecture_teach_pass($post,$data)
        {
            $this->db->insert('user_info',$data);
            if($this->db->update('user',array('user.teach_priv'=>1),array('user.id'=>$post['user_id'])) && $this->db->update('teacher_lecture',array('teacher_lecture.status'=>4),array('teacher_lecture.id'=>$post['lecture_id'])) && $this->db->insert('user_info',$data))
             {
                 return TRUE;
             }
        }
        /**
         *待定试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function lecture_teach_indeterminate($lecture_id)
        {
            return $this->db->update('teacher_lecture',array('teacher_lecture.status'=>2),array('teacher_lecture.id'=>$lecture_id));
        }
        /**
         *不通过试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function lecture_teach_nopass($lecture_id)
        {
            return $this->db->update('teacher_lecture',array('teacher_lecture.status'=>3),array('teacher_lecture.id'=>$lecture_id));
        }
    }