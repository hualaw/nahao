<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 12-6-4
 * Time: 下午3:49
 * To change this template use File | Settings | File Templates.
 */
class NH_Student_Controller extends NH_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->isLogin(ROLE_STUDENT);
        if($this->user){

        }
        /*end*/

    }
}
