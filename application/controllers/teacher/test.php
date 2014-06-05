<?php
/**
 * Created by PhpStorm.
 * User: 91waijiao
 * Date: 14-5-29
 * Time: 下午2:15
 */

class test extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 老师首页
     */
    public function index(){
        $this->smarty->display('teacher/teacherHomePage/index.html');
    }
    public function order1(){
        $this->smarty->display('teacher/teacherOrderList/index.html');
    }
    public function order2(){
        $this->smarty->display('teacher/teacherOrderList/order_appraise.html');
    }
    public function order3(){
        $this->smarty->display('teacher/teacherOrderList/order_count.html');
    }
    public function order4(){
        $this->smarty->display('teacher/teacherOrderList/order_detail.html');
    }
    public function order5(){
        $this->smarty->display('teacher/teacherOrderList/order_manage.html');
    }
    public function pay1(){
        $this->smarty->display('teacher/teacherPay/index.html');
    }
    public function pay2(){
        $this->smarty->display('teacher/teacherPay/pay_detail.html');
    }
    public function info1(){
        $this->smarty->display('teacher/teacherSelfinfo/index.html');
    }
    public function info2(){
        $this->smarty->display('teacher/teacherSelfinfo/openClass.html');
    }
    public function info3(){
        $this->smarty->display('teacher/teacherSelfinfo/password.html');
    }
    public function info4(){
        $this->smarty->display('teacher/teacherSelfinfo/photo.html');
    }
    public function info5(){
        $this->smarty->display('teacher/teacherSelfinfo/success.html');
    }
    public function login(){
        $this->smarty->display('www/login/teacherLogin.html');
    }
} 