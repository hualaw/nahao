<?php

class MyTest extends CI_Controller{
    
    /**
     * smarty 缓存测试
     */
    public function smarty()
    {  
        $time = date('H:i:s');
        $template='www/mytest/smarty.html';

        $page = 1;
        $cache_id = $page;
        //cache_id是用来区分由于参数不同而生成的不同页面,例如有两个参数page和type，可用 $cache_id = $page.'_'.$type; 
        if(!$this->smarty->isCached($template, $cache_id))
        {
            $this->smarty->assign('time_cache', $time);
        }
        $this->smarty->assign('time_nocache', $time);
        $this->smarty->display($template,$cache_id);
    }
    /*
    public function oauth(){
        var_dump($this->session->userdata('phone'));
    }
    */

    public function hash_test()
    {
        $hash_value = nahao_hash('abcd', 4);
        echo $hash_value;
    }
} 
