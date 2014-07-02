<?php
    class Business_affiche extends NH_Model
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/admin/model_affiche');
        }
        /**
         * 公告展示
         * @author shangshikai@tizi.com
         */
        public function list_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id)
        {
            $admin_name=trim($admin_name);
            $content=trim($content);
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            $arr=$this->model_affiche->affiche_list($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id);
            foreach($arr as $k=>$v)
            {
                $arr[$k]['content']=htmlspecialchars_decode( $arr[$k]['content']);
                $arr[$k]['content']=strip_tags($arr[$k]['content']);
            }

            return $arr;
        }
        /**
         * 公告数量
         * @author shangshikai@tizi.com
         */
        public function total_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id)
        {
            $admin_name=trim($admin_name);
            $content=trim($content);
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            return $this->model_affiche->affiche_total($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id);
        }
        /**
         * 公告编辑
         * @author shangshikai@tizi.com
         */
        public function content_edit($post)
        {
            $post['content']=trim(htmlspecialchars($post['content']));
            if($post['content']=="")
            {
                return FALSE;
            }
            return $this->model_affiche->modify_content($post);
        }
        /**
         * 公告通过审核
         * @author shangshikai@tizi.com
         */
        public function affiche_pass($id)
        {
            return $this->model_affiche->pass_affiche($id);
        }
        /**
         * 公告不通过审核
         * @author shangshikai@tizi.com
         */
        public function affiche_nopass($id)
        {
            return $this->model_affiche->nopass_affiche($id);
        }
        /**
         * 公告置顶
         * @author shangshikai@tizi.com
         */
        public function affiche_top($id)
        {
            return $this->model_affiche->top_affiche($id);
        }
        /**
         * 公告取消置顶
         * @author shangshikai@tizi.com
         */
        public function affiche_notop($id)
        {
            return $this->model_affiche->notop_affiche($id);
        }
        /**
         * 添加公告
         * @author shangshikai@tizi.com
         */
        public function affiche_insert($post)
        {
            $post['content']=trim(htmlspecialchars($post['content']));
            if($post['content']=="")
            {
                return FALSE;
            }
            if($post['role']==2)//2是管理员发的 1是老师发的
            {
                //管理员发公告
                $admin_id=$this->userinfo['id'];
                $data=array(
                    'round_id'=>$post['round_id'],
                    'author'=>$admin_id,
                    'author_role'=>2,
                    'content'=>$post['content'],
                    'create_time'=>time(),
                    'status'=>1,
                    'top_time'=>0
                );
            }
            if($post['role']==1)
            {
                //老师发公告,目前还没有此功能
                $data=array(
                    'round_id'=>$post['round_id'],
                    'author'=>$post['teacher_id'],
                    'author_role'=>1,
                    'content'=>$post['content'],
                    'create_time'=>time(),
                    'status'=>1,
                    'top_time'=>0
                );
            }
            return $this->model_affiche->add_affiche($data);
        }
    }