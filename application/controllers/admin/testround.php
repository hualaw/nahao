<?php
    class Testround extends NH_Admin_Controller {
        public function index()
        {
            Header( "Content-type:application/octet-stream ");
            Header( "Accept-Ranges:bytes ");
            Header( "Content-type:application/vnd.ms-excel ");
            Header( "Content-Disposition:attachment;filename=test.xls ");
            $arr=$this->testround->test();
            echo "ID\tname\tphone";
            foreach($arr as $v)
            {
                echo "\n";
                echo iconv('utf-8','gbk',$v['user_id'])."\t".iconv('utf-8','gbk',$v['realname'])."\t".iconv('utf-8','gbk',$v['tel']);
            }
        }
    }
