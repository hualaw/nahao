<?php
    class Business_identifying extends NH_Model
    {
        /**
         * 查询验证码
         * @author shangshikai@tizi.com
         */
        public function code_dentifying($phone)
        {
            $arr_vc=array();
            $this->load->model('model/common/model_redis', 'redis');
            $this->redis->connect('login');
            $redis_data=$this->cache->redis->llen($phone);
            if($redis_data >= 1)
            {
                $captcha_info_arr = $this->cache->redis->lrange($phone, 0, -1);
                foreach($captcha_info_arr as $v)
                {
                    $arr_vc[]= json_decode($v,TRUE);
                }
            }
            return $arr_vc;
        }
    }