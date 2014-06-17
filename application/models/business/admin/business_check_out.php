<?php
    class business_check_out extends NH_Model
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/admin/model_check_out');
        }
        /**
         * 总课酬数
         * @author shangshikai@tizi.com
         */
        public function check_out_total()
        {
            return $this->model_check_out->total_check_out();
        }
        /**
         * 课酬统计
         * @author shangshikai@tizi.com
         */
        public function statistics_amount()
        {
            $check_count=$this->model_check_out->amount_statistics();
            $check_count['balance_income']=0;
            $check_count['unsettled_income']=0;
            $check_count['pay_income']=0;
            foreach($check_count['balance_net_income'] as $v)
            {
                $check_count['balance_income']+=$v['net_income'];
            }

            foreach($check_count['unsettled_net_income'] as $v)
            {
                $check_count['unsettled_income']+=$v['net_income'];
            }

            foreach($check_count['pay_net_income'] as $v)
            {
                $check_count['pay_income']+=$v['net_income'];
            }
            //var_dump($check_count);die;
            unset($check_count['balance_net_income']);
            unset($check_count['unsettled_net_income']);
            unset($check_count['pay_net_income']);
            return $check_count;
        }
        /**
         * 课酬列表
         * @author shangshikai@tizi.com
         */
        public function for_class_list()
        {
            return $this->model_check_out->list_class();
        }
        /**
         * 课酬搜索
         * @author shangshikai@tizi.com
         */
        public function search_accounts_list($get)
        {
            $get['term_val']=trim($get['term_val']);
            $get['settle_time']=strtotime($get['settle_time']);
            $year=date('Y',$get['settle_time']);
            $mon=date('m',$get['settle_time']);
            $get['next_mon'] = mktime(0,0,0,$mon+1,1,$year);
            //var_dump($get);die;
            return $this->model_check_out->accounts_search_list($get);
        }
        /**
         * 搜索课酬总数
         * @author shangshikai@tizi.com
         */
        public function search_accounts_count($get)
        {
            $get['term_val']=trim($get['term_val']);
            $get['settle_time']=strtotime($get['settle_time']);
            $year=date('Y',$get['settle_time']);
            $mon=date('m',$get['settle_time']);
            $get['next_mon'] = mktime(0,0,0,$mon+1,1,$year);
            //var_dump($get);die;
            return $this->model_check_out->accounts_search_count($get);
        }
        /**
         * 课酬详情
         * @author shangshikai@tizi.com
         */
        public function details($class_ids)
        {
            $arr_class=array();
            $arr_class_ids=explode(',',$class_ids);
            foreach($arr_class_ids as $k=>$v)
            {
                $arr_class[$k]=$this->model_check_out->detail($v);
            }
            return $arr_class;
        }
        /**
         * 课酬详情统计
         * @author shangshikai@tizi.com
         */
        public function details_count($checkout_id)
        {
            return $this->model_check_out->count_details($checkout_id);
        }
        /**
         * 确认结算
         * @author shangshikai@tizi.com
         */
        public function checkout_ok($check_id)
        {
            return $this->model_check_out->check_out_ok($check_id);
        }
        /**
         * 确认付款
         * @author shangshikai@tizi.com
         */
        public function pay_ok($user_id,$check_id,$net_income)
        {
            return $this->model_check_out->pay_success($user_id,$check_id,$net_income);
        }
    }