<?php

class NH_Auto_Controller extends NH_Controller
{
    function __construct()
    {
//        if(PHP_SAPI !== 'cli')
//        {
//            die('No direct script access allowed');
//        }
        set_time_limit(3600);
        ini_set('memory_limit', '128M');
        parent::__construct();
    }
}
